<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectInvoice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectInvoicesController extends Controller
{
    function __construct()
    {
        $this->middleware('check.permission:invoices_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_invoice', ['only' => ['create', 'store', 'bulkAssign']]);
        $this->middleware('check.permission:edit_invoice', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:show_invoice', ['only' => ['show']]);
        $this->middleware('check.permission:approve_invoice', ['only' => ['approve', 'reject']]);
        $this->middleware('check.permission:delete_invoice', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProjectInvoice::with(['project', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $invoices = $query->paginate(50);

        return view('dashboard.project-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.project-invoices.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:255|unique:project_invoices',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->project_id && $request->amount) {
                $project = Project::find($request->project_id);
                if ($project && $request->amount > $project->remaining_amount) {
                    $validator->errors()->add(
                        'amount',
                        'Payment amount cannot exceed the remaining project amount of $' .
                        number_format($project->remaining_amount, 2)
                    );
                }
            }
        });

        if ($validator->fails()) {
            notify($validator->errors()->first(), 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $fileName = time() . '_' . uniqid() . '.' . $request->file('payment_file')->getClientOriginalExtension();
            $filePath = $request->file('payment_file')->storeAs('invoices', $fileName, 'public');

            ProjectInvoice::create([
                'project_id' => $request->project_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'payment_file' => $filePath,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            DB::commit();
            notify('Invoice uploaded successfully and is pending approval.', 'success');
            return redirect()->route('invoices.index');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            notify('Failed to upload invoice: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $invoice = ProjectInvoice::with(['project', 'approvedBy'])->findOrFail($id);
        return view('dashboard.project-invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = ProjectInvoice::with('project')->findOrFail($id);
        $projects = Project::orderBy('name')->get();

        return view('dashboard.project-invoices.edit', compact('invoice', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:255|unique:project_invoices,invoice_number,' . $projectInvoice->id,
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validator->after(function ($validator) use ($request, $projectInvoice) {
            if ($request->project_id && $request->amount) {
                $project = Project::find($request->project_id);
                if ($project) {
                    $availableAmount = $project->remaining_amount;
                    if ($projectInvoice->status === 'approved') {
                        $availableAmount += $projectInvoice->amount;
                    }
                    if ($request->amount > $availableAmount) {
                        $validator->errors()->add(
                            'amount',
                            'Payment amount cannot exceed the available project amount of $' .
                            number_format($availableAmount, 2)
                        );
                    }
                }
            }
        });

        if ($validator->fails()) {
            notify($validator, 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'project_id' => $request->project_id,
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ];

        if ($request->hasFile('payment_file')) {
            if ($projectInvoice->payment_file) {
                Storage::disk('public')->delete($projectInvoice->payment_file);
            }
            $updateData['payment_file'] = $request->file('payment_file')->store('invoices', 'public');
        }

        if ($projectInvoice->status === 'approved' && $projectInvoice->amount != $request->amount) {
            $updateData['status'] = 'pending';
            $updateData['approved_at'] = null;
            $updateData['approved_by'] = null;
        }

        $projectInvoice->update($updateData);

        notify('Invoice updated successfully.', 'success');
        return redirect()->route('invoices.show', $projectInvoice->id);
    }

    public function destroy($id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);

        if ($projectInvoice->payment_file) {
            Storage::disk('public')->delete($projectInvoice->payment_file);
        }

        $projectInvoice->delete();
        notify('Invoice deleted successfully.', 'success');
        return redirect()->route('invoices.index');
    }

    public function approve(Request $request, $id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);

        if ($projectInvoice->status !== 'pending') {
            notify('Only pending invoices can be approved.', 'error');
            return redirect()->back();
        }

        $project = $projectInvoice->project;
        if ($projectInvoice->amount > $project->remaining_amount) {
            notify('Invoice amount exceeds remaining project balance.', 'error');
            return redirect()->back();
        }

        $projectInvoice->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $request->notes ?? $projectInvoice->notes,
        ]);

        if ($project->is_fully_paid) {
            $project->update(['status' => 'delivered']);
        }

        notify('Invoice approved successfully.', 'success');
        return redirect()->back();
    }

    public function reject(Request $request, $id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);

        if ($projectInvoice->status !== 'pending') {
            notify('Only pending invoices can be rejected.', 'error');
            return redirect()->back();
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $projectInvoice->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        notify('Invoice rejected successfully.', 'success');
        return redirect()->back();
    }
}
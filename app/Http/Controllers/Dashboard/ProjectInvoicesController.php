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
    public function index()
    {
        $invoices = ProjectInvoice::with(['project', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.project-invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.project-invoices.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:255|unique:project_invoices',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB
            'notes' => 'nullable|string|max:1000',
        ]);

        // Custom validation for amount not exceeding remaining project amount
        $validator->after(function ($validator) use ($request) {
            if ($request->project_id && $request->amount) {
                $project = Project::find($request->project_id);
                if ($project && $request->amount > $project->remaining_amount) {
                    $validator->errors()->add(
                        'amount',
                        'Payment amount cannot exceed the remaining project amount of $'
                            . number_format($project->remaining_amount, 2)
                    );
                }
            }
        });

        if ($validator->fails()) {
            notify($validator->errors()->first(), 'error');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // رفع الملف باسم فريد
            $fileName = time() . '_' . uniqid() . '.' . $request->file('payment_file')->getClientOriginalExtension();
            $filePath = $request->file('payment_file')->storeAs('invoices', $fileName, 'public');

            // إنشاء الفاتورة
            ProjectInvoice::create([
                'project_id' => $request->project_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'payment_file' => $filePath, // بيتخزن المسار فقط
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            DB::commit();

            notify('Invoice uploaded successfully and is pending approval.', 'success');
            return redirect()->route('invoices.index');
        } catch (\Exception $e) {
            DB::rollBack();

            // لو الملف اترفع واحنا فشلنا في التخزين بالداتا بيز
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            notify('Failed to upload invoice: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);
        $invoice = $projectInvoice->load(['project', 'approvedBy']);
        return view('dashboard.project-invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $projectInvoice = ProjectInvoice::findOrFail($id);
        $invoice = $projectInvoice->load('project');
        $projects = Project::orderBy('name')->get();

        return view('dashboard.project-invoices.edit', compact('invoice', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $projectInvoice = ProjectInvoice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:255|unique:project_invoices,invoice_number,' . $projectInvoice->id,
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB
            'notes' => 'nullable|string|max:1000',
        ]);

        // Custom validation for amount
        $validator->after(function ($validator) use ($request, $projectInvoice) {
            if ($request->project_id && $request->amount) {
                $project = Project::find($request->project_id);
                if ($project) {
                    // Calculate available amount (remaining + current invoice amount if approved)
                    $availableAmount = $project->remaining_amount;
                    if ($projectInvoice->status === 'approved') {
                        $availableAmount += $projectInvoice->amount;
                    }

                    if ($request->amount > $availableAmount) {
                        $validator->errors()->add('amount', 'Payment amount cannot exceed the available project amount of $' . number_format($availableAmount, 2));
                    }
                }
            }
        });

        if ($validator->fails()) {

            notify($validator, 'error');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'project_id' => $request->project_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ];

            // Handle file upload if provided
            if ($request->hasFile('payment_file')) {
                // Delete old file
                if ($projectInvoice->payment_file) {
                    Storage::disk('public')->delete($projectInvoice->payment_file);
                }

                // Upload new file
                $updateData['payment_file'] = $request->file('payment_file')->store('invoices', 'public');
            }

            // If invoice was approved and amount changed, reset to pending
            if ($projectInvoice->status === 'approved' && $projectInvoice->amount != $request->amount) {
                $updateData['status'] = 'pending';
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            }

            $projectInvoice->update($updateData);

            notify('Invoice updated successfully.', 'success');

            return redirect()->route('invoices.show', $projectInvoice->id);
        } catch (\Exception $e) {

            notify('Failed to update invoice: ' . $e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try {

            $projectInvoice = ProjectInvoice::findOrFail($id);

            // Delete file
            if ($projectInvoice->payment_file) {
                Storage::disk('public')->delete($projectInvoice->payment_file);
            }

            $projectInvoice->delete();

            notify('Invoice deleted successfully.', 'success');

            return redirect()->route('invoices.index');
        } catch (\Exception $e) {

            notify('Failed to delete invoice: ' . $e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Approve an invoice
     */
    public function approve(Request $request, $id)
    {

        $projectInvoice = ProjectInvoice::findOrFail($id);

        if ($projectInvoice->status !== 'pending') {

            notify('Only pending invoices can be approved.', 'error');

            return redirect()->back();
        }

        // Check if amount doesn't exceed remaining project balance
        $project = $projectInvoice->project;
        if ($projectInvoice->amount > $project->remaining_amount) {

            notify('Invoice amount exceeds remaining project balance.', 'error');

            return redirect()->back();
        }

        try {
            $projectInvoice->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'notes' => $request->notes ?? $projectInvoice->notes,
            ]);

            // Check if project is now fully paid and update status
            if ($project->is_fully_paid) {
                $project->update(['status' => 'delivered']); // or whatever status indicates full payment
            }

            notify('Invoice approved successfully. Project balance updated.', 'success');

            return redirect()->back();
        } catch (\Exception $e) {

            notify('Failed to approve invoice: ' . $e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Reject an invoice
     */
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

        try {
            $projectInvoice->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'notes' => $request->notes,
            ]);

            notify('Invoice rejected successfully.', 'success');

            return redirect()->back();
        } catch (\Exception $e) {

            notify('Failed to reject invoice: ' . $e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Get project invoices via AJAX
     */
    public function getProjectInvoices(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);

        $invoices = $project->invoices()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'invoices' => $invoices,
            'project' => [
                'total_cost' => $project->project_cost,
                'total_paid' => $project->total_paid,
                'remaining_amount' => $project->remaining_amount,
                'payment_progress' => $project->payment_progress,
                'is_fully_paid' => $project->is_fully_paid,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ProjectAmer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceAmer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InvoicesAmerController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:invoices_amer_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_invoice_amer', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_invoice_amer', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:show_invoice_amer', ['only' => ['show']]);
        $this->middleware('check.permission:approve_invoice_amer', ['only' => ['approve', 'reject', 'updateStatus']]);
        $this->middleware('check.permission:delete_invoice_amer', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = InvoiceAmer::with(['projectAmer', 'approvedBy', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $availableStatuses = \App\Models\InvoiceAmer::STATUSES;
        return view('dashboard.invoices_amer.index', compact('invoices', 'availableStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = ProjectAmer::whereDoesntHave('invoice')->orderBy('created_at', 'desc')->get();

        return view('dashboard.invoices_amer.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_amer_id' => 'required|exists:project_amers,id',
            'invoice_number' => 'required|string|max:255|unique:invoice_amers',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            notify($validator->errors()->first(), 'error');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $fileName = time() . '_' . uniqid() . '.' . $request->file('payment_file')->getClientOriginalExtension();
            $filePath = $request->file('payment_file')->storeAs('invoices_amer', $fileName, 'public');

            InvoiceAmer::create([
                'project_amer_id' => $request->project_amer_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'payment_file' => $filePath,
                'status' => 'pending',
                'date' => $request->date,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            notify('Invoice uploaded successfully and is pending approval.', 'success');
            return redirect()->route('invoices_amer.index');
        } catch (\Exception $e) {
            DB::rollBack();

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
        $invoice = InvoiceAmer::with(['projectAmer', 'approvedBy', 'createdBy'])->findOrFail($id);
        $availableStatuses = \App\Models\InvoiceAmer::STATUSES;
        return view('dashboard.invoices_amer.show', compact('invoice', 'availableStatuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = InvoiceAmer::with('projectAmer')->findOrFail($id);
        $projects = ProjectAmer::orderBy('created_at', 'desc')->get();

        return view('dashboard.invoices_amer.edit', compact('invoice', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = InvoiceAmer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'project_amer_id' => 'required|exists:project_amers,id',
            'invoice_number' => 'required|string|max:255|unique:invoice_amers,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            notify($validator, 'error');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'project_amer_id' => $request->project_amer_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'date' => $request->date,
                'notes' => $request->notes,
            ];

            if ($request->hasFile('payment_file')) {
                if ($invoice->payment_file) {
                    Storage::disk('public')->delete($invoice->payment_file);
                }

                $updateData['payment_file'] = $request->file('payment_file')->store('invoices_amer', 'public');
            }

            if ($invoice->status !== 'pending' && $invoice->amount != $request->amount) {
                $updateData['status'] = 'pending';
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            }

            $invoice->update($updateData);

            notify('Invoice updated successfully.', 'success');

            return redirect()->route('invoices_amer.show', $invoice->id);
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
            $invoice = InvoiceAmer::findOrFail($id);

            if ($invoice->payment_file) {
                Storage::disk('public')->delete($invoice->payment_file);
            }

            $invoice->delete();

            notify('Invoice deleted successfully.', 'success');

            return redirect()->route('invoices_amer.index');
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
        $invoice = InvoiceAmer::findOrFail($id);

        if ($invoice->status !== 'pending') {
            notify('Only pending invoices can be approved.', 'error');
            return redirect()->back();
        }

        try {
            $invoice->update([
                'status' => 'submitted',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'notes' => $request->notes ?? $invoice->notes,
            ]);

            notify('Invoice approved successfully.', 'success');

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
        $invoice = InvoiceAmer::findOrFail($id);

        if ($invoice->status !== 'pending') {
            notify('Only pending invoices can be rejected.', 'error');
            return redirect()->back();
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        try {
            $invoice->update([
                'status' => 'canceled',
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
     * Update invoice status
     */
    public function updateStatus(Request $request, $id)
    {
        $invoice = InvoiceAmer::findOrFail($id);

        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\InvoiceAmer::STATUSES),
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $newStatus = $request->status;

            $updateData = [
                'status' => $newStatus,
                'notes' => $request->notes ?? $invoice->notes,
            ];

            if ($newStatus === 'pending') {
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            } else {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = auth()->id();
            }

            $invoice->update($updateData);

            notify('Invoice status updated successfully.', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            notify('Failed to update status: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Get project invoices via AJAX
     */
    public function getProjectInvoices(Request $request, $project_id)
    {
        $project = ProjectAmer::findOrFail($project_id);

        $invoices = InvoiceAmer::where('project_amer_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'invoices' => $invoices,
            'project' => [
                'id' => $project->id,
                'po_num' => $project->po_num,
            ]
        ]);
    }
}

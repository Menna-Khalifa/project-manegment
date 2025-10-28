@extends('dashboard.layouts.master')

@section('title')
    Invoice Details
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices.index') }}">Project Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Invoice Details
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">Invoice: {{ $invoice->invoice_number }}</h4>
                        <div>
                            @can('edit_invoice')
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary mr-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Invoice Status Card -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-primary-gradient text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Invoice Status</h6>
                                            @if($invoice->status === 'pending')
                                                <h3 class="mb-0"><span class="badge badge-warning">Pending Review</span></h3>
                                            @elseif($invoice->status === 'approved')
                                                <h3 class="mb-0"><span class="badge badge-success">Approved</span></h3>
                                            @else
                                                <h3 class="mb-0"><span class="badge badge-danger">Rejected</span></h3>
                                            @endif
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-invoice fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Amount Card -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-success-gradient text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Payment Amount</h6>
                                            <h3 class="mb-0">{{ number_format($invoice->amount, 2) }} SAR</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Date Card -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-info-gradient text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Uploaded Date</h6>
                                            <h5 class="mb-0">{{ $invoice->created_at->format('M d, Y') }}</h5>
                                            <small>{{ $invoice->created_at->format('h:i A') }}</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Details -->
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Invoice Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold" style="width: 200px;">Invoice Number:</td>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Project:</td>
                                                    <td>
                                                        <strong>{{ $invoice->project->name }}</strong><br>
                                                        <small class="text-muted">PO Number: {{ $invoice->project->po_num }}</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Type:</td>
                                                    <td>{{ $invoice->project->type }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Payment Amount:</td>
                                                    <td>
                                                        <h5 class="text-success mb-0">{{ number_format($invoice->amount, 2) }} SAR</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Status:</td>
                                                    <td>
                                                        @if($invoice->status === 'pending')
                                                            <span class="badge badge-warning badge-lg">Pending Review</span>
                                                        @elseif($invoice->status === 'approved')
                                                            <span class="badge badge-success badge-lg">Approved</span>
                                                        @else
                                                            <span class="badge badge-danger badge-lg">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Uploaded Date:</td>
                                                    <td>{{ $invoice->created_at->format('F d, Y \a\t h:i A') }}</td>
                                                </tr>
                                                @if($invoice->approved_at)
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        {{ $invoice->status === 'approved' ? 'Approved' : 'Rejected' }} Date:
                                                    </td>
                                                    <td>{{ $invoice->approved_at->format('F d, Y \a\t h:i A') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        {{ $invoice->status === 'approved' ? 'Approved' : 'Rejected' }} By:
                                                    </td>
                                                    <td>{{ $invoice->approvedBy->name ?? 'Unknown' }}</td>
                                                </tr>
                                                @endif
                                                @if($invoice->notes)
                                                <tr>
                                                    <td class="font-weight-bold">Notes:</td>
                                                    <td>
                                                        <div class="p-2 border rounded bg-light">
                                                            {{ $invoice->notes }}
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment File and Actions -->
                        <div class="col-lg-4">
                            <!-- Payment Document -->
                            @can('view_payment_invoice')
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Payment Document</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($invoice->payment_file)
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-5x text-primary mb-3"></i>
                                            <p class="mb-2">
                                                <strong>{{ basename($invoice->payment_file) }}</strong>
                                            </p>
                                        </div>
                                        <a href="{{ asset('storage/' . $invoice->payment_file) }}"
                                           target="_blank"
                                           class="btn btn-primary btn-block">
                                            <i class="fas fa-eye"></i> View Document
                                        </a>
                                        <a href="{{ asset('storage/' . $invoice->payment_file) }}"
                                           download
                                           class="btn btn-success btn-block mt-2">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        <p class="text-muted">No document uploaded</p>
                                    @endif
                                </div>
                            </div>
                            @endcan

                            <!-- Admin Actions -->
                            @if($invoice->status === 'pending' && auth()->user()->can('approve_invoice'))
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Admin Actions</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button"
                                            class="btn btn-success btn-block mb-2"
                                            data-toggle="modal"
                                            data-target="#approveModal">
                                        <i class="fas fa-check"></i> Approve Invoice
                                    </button>
                                    <button type="button"
                                            class="btn btn-warning btn-block"
                                            data-toggle="modal"
                                            data-target="#rejectModal">
                                        <i class="fas fa-times"></i> Reject Invoice
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Project Financial Summary -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Project Financial Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-muted mb-2">Total Project Cost</h6>
                                                <h4 class="text-dark mb-0">{{ number_format($invoice->project->project_cost, 2) }} SAR</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-muted mb-2">Total Paid</h6>
                                                <h4 class="text-success mb-0">{{ number_format($invoice->project->total_paid, 2) }} SAR</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-muted mb-2">Remaining Amount</h6>
                                                <h4 class="text-warning mb-0">{{ number_format($invoice->project->remaining_amount, 2) }} SAR</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-muted mb-2">Payment Progress</h6>
                                                <h4 class="text-info mb-0">{{ number_format($invoice->project->payment_progress, 1) }}%</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Payment Progress</span>
                                            <span class="text-muted">{{ number_format($invoice->project->payment_progress, 1) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success"
                                                 role="progressbar"
                                                 style="width: {{ $invoice->project->payment_progress }}%"
                                                 aria-valuenow="{{ $invoice->project->payment_progress }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    @if($invoice->project->is_fully_paid)
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>Project Fully Paid!</strong> This project has been fully paid.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Invoices -->
                    @if($invoice->project->invoices->count() > 1)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Other Project Invoices</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Invoice Number</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    @can('show_invoice')
                                                    <th>Actions</th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice->project->invoices->where('id', '!=', $invoice->id) as $otherInvoice)
                                                <tr>
                                                    <td>{{ $otherInvoice->invoice_number }}</td>
                                                    <td>{{ number_format($otherInvoice->amount, 2) }} SAR</td>
                                                    <td>
                                                        @if($otherInvoice->status === 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif($otherInvoice->status === 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $otherInvoice->created_at->format('M d, Y') }}</td>
                                                    @can('show_invoice')
                                                    <td>
                                                        <a href="{{ route('invoices.show', $otherInvoice->id) }}"
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                    @endcan
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('invoices.approve', $invoice->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Invoice:</strong> {{ $invoice->invoice_number }}<br>
                            <strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}<br>
                            <strong>Project:</strong> {{ $invoice->project->name }}
                        </div>
                        <div class="form-group">
                            <label for="approve_notes">Approval Notes (Optional):</label>
                            <textarea class="form-control" name="notes" id="approve_notes" rows="3"
                                      placeholder="Add any notes about this approval..."></textarea>
                        </div>
                        <p class="text-success">
                            <i class="fas fa-info-circle"></i>
                            This amount will be deducted from the project's remaining balance.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approve Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('invoices.reject', $invoice->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Invoice:</strong> {{ $invoice->invoice_number }}<br>
                            <strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}<br>
                            <strong>Project:</strong> {{ $invoice->project->name }}
                        </div>
                        <div class="form-group">
                            <label for="reject_notes">Rejection Reason <span class="text-danger">*</span>:</label>
                            <textarea class="form-control" name="notes" id="reject_notes" rows="3"
                                      placeholder="Please specify the reason for rejection..." required></textarea>
                        </div>
                        <p class="text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            This invoice will be marked as rejected and no amount will be deducted from the project balance.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-times"></i> Reject Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut();
    }, 5000);
});
</script>
@endsection

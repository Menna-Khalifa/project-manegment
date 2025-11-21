<!-- resources/views/dashboard/invoices_amer/show.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Amer Invoice Details
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices_amer.index') }}">Amer Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Invoice Details
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mg-b-0">Invoice: {{ $invoice->invoice_number }}</h4>
                    <div>
                        @can('edit_invoice_amer')
                        <a href="{{ route('invoices_amer.edit', $invoice->id) }}" class="btn btn-primary mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        <a href="{{ route('invoices_amer.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold" style="width: 220px;">Amer Project</td>
                                            <td>{{ $invoice->projectAmer->po_num ?? '#' . $invoice->projectAmer->id }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Invoice Number</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Amount</td>
                                            <td>{{ number_format($invoice->amount, 2) }} SAR</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Status</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Created By</td>
                                            <td>{{ $invoice->createdBy->name ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Approved By</td>
                                            <td>{{ $invoice->approvedBy->name ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Notes</td>
                                            <td>{{ $invoice->notes ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Extras</td>
                                            <td>
                                                Crane: {{ $invoice->crane ? 'Yes' : 'No' }} ({{ $invoice->amount_crane }})
                                                |
                                                Capper Pipe: {{ $invoice->capper_pipe ? 'Yes' : 'No' }}
                                                ({{ $invoice->amount_capper_pipe }}) |
                                                Power Cable: {{ $invoice->power_cable ? 'Yes' : 'No' }}
                                                ({{ $invoice->amount_power_cable }})
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            @if ($invoice->payment_file)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Payment Document</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-5x text-primary mb-3"></i>
                                        <p class="mb-2"><strong>{{ basename($invoice->payment_file) }}</strong></p>
                                        <a href="{{ asset('storage/' . $invoice->payment_file) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> View File
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @can('approve_invoice_amer')
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($invoice->status === 'pending')
                                            <form action="{{ route('invoices_amer.approve', $invoice->id) }}" method="POST"
                                                class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fas fa-check"></i> Approve (Submit)
                                                </button>
                                            </form>
                                            <form action="{{ route('invoices_amer.reject', $invoice->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <textarea name="notes" class="form-control" rows="2" placeholder="Reason" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-block">
                                                    <i class="fas fa-times"></i> Reject (Cancel)
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-info">Current status:
                                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endcan

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

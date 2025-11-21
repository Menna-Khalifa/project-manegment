@extends('dashboard.layouts.master')

@section('title')
    ProjectAmer Details - {{ $project_amer->po_num }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_amers.index') }}">ProjectAmer</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ $project_amer->po_num }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>ProjectAmer Information</h5>
                    <div class="btn-group">
                        @can('edit_project_amers')
                            <a href="{{ route('project_amers.edit', $project_amer->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endcan
                        <a href="{{ route('project_amers.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        @can('show_invoice_amer')
                        @if($project_amer->invoice)
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#invoiceModal{{ $project_amer->invoice->id }}">
                                <i class="las la-file-invoice"></i> View Invoice
                            </button>
                        @endif
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">PO Number</label>
                                <div class="info-value">{{ $project_amer->po_num }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Department</label>
                                <div class="info-value">{{ $project_amer->dept }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Region</label>
                                <div class="info-value">{{ $project_amer->region }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Store</label>
                                <div class="info-value">{{ $project_amer->store->name ?? '-' }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">User</label>
                                <div class="info-value">{{ $project_amer->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Date</label>
                                <div class="info-value">
                                    {{ is_string($project_amer->date) ? $project_amer->date : ($project_amer->date ? $project_amer->date->format('M d, Y') : '-') }}
                                </div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Priority</label>
                                @php
                                    $priorityClass = match ($project_amer->priority) {
                                        'high' => 'danger',
                                        'medium' => 'warning',
                                        'low' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <div class="info-value"><span
                                        class="badge badge-{{ $priorityClass }}">{{ ucfirst($project_amer->priority) }}</span>
                                </div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Status</label>
                                @php
                                    $statusClass = match ($project_amer->request_status) {
                                        'new_order' => 'primary',
                                        'under_working' => 'info',
                                        'completed' => 'success',
                                        'on_hold' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <div class="info-value"><span
                                        class="badge badge-{{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($project_amer->request_status)) }}</span>
                                </div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Amount</label>
                                <div class="info-value text-success font-weight-bold">
                                    {{ number_format($project_amer->amount, 2) }} SAR</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">PO File</label>
                                <div class="info-value">
                                    @if ($project_amer->po_file)
                                        <a href="{{ asset('storage/' . $project_amer->po_file) }}" target="_blank"
                                            class="btn btn-outline-info btn-sm">View</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">Notes</label>
                                <div class="info-value">{{ $project_amer->notes ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($project_amer->dept == 'maintenance')
        <div class="row mt-3">
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-header border-bottom">
                        <h5 class="mb-0">Maintenance Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $m = 1; @endphp
                                    @foreach ($project_amer->items->filter(fn($i) => $i->project_model_id) as $item)
                                        <tr>
                                            <td>{{ $m++ }}</td>
                                            <td>{{ $item->projectType->name ?? '-' }}</td>
                                            <td>{{ $item->projectModel->name ?? '-' }}</td>
                                            <td>{{ $item->qty }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($project_amer->items->filter(fn($i) => $i->project_model_id)->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center">No maintenance items</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($project_amer->dept != 'maintenance')
        <div class="row mt-3">
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-header border-bottom">
                        <h5 class="mb-0">Project Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Volt</th>
                                        <th>Brand</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $p = 1; @endphp
                                    @foreach ($project_amer->items->filter(fn($i) => $i->project_capacity_id || $i->project_volt_id || $i->brand_id) as $item)
                                        <tr>
                                            <td>{{ $p++ }}</td>
                                            <td>{{ $item->projectType->name ?? '-' }}</td>
                                            <td>{{ $item->projectCapacity->name ?? '-' }}</td>
                                            <td>{{ $item->projectVolt->value ?? '-' }}</td>
                                            <td>{{ $item->brand->name ?? '-' }}</td>
                                            <td>{{ $item->qty }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($project_amer->items->filter(fn($i) => $i->project_capacity_id || $i->project_volt_id || $i->brand_id)->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">No project items</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Invoice Modals -->
        <div class="modal fade" id="invoiceModal{{ $project_amer->invoice->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Invoice Details - {{ $project_amer->invoice->invoice_number }}</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="info-label">Invoice Number</label>
                                    <div class="info-value">{{ $project_amer->invoice->invoice_number }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Amount</label>
                                    <div class="info-value text-success">{{ number_format($project_amer->invoice->amount, 2) }} SAR</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">
                                        @php $status = $project_amer->invoice->status; @endphp
                                        @if ($status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($status === 'submitted')
                                            <span class="badge badge-info">Submitted</span>
                                        @elseif ($status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif ($status === 'ready_of_invoicing')
                                            <span class="badge badge-primary">Ready Of Invoicing</span>
                                        @elseif ($status === 'invoice_issuse')
                                            <span class="badge badge-dark">Invoice Issue</span>
                                        @elseif ($status === 'canceled')
                                            <span class="badge badge-danger">Canceled</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_',' ', $status)) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Extras</label>
                                    <div class="info-value">
                                        Crane: {{ $project_amer->invoice->crane ? 'Yes' : 'No' }} ({{ $project_amer->invoice->amount_crane }}) |
                                        Capper Pipe: {{ $project_amer->invoice->capper_pipe ? 'Yes' : 'No' }} ({{ $project_amer->invoice->amount_capper_pipe }}) |
                                        Power Cable: {{ $project_amer->invoice->power_cable ? 'Yes' : 'No' }} ({{ $project_amer->invoice->amount_power_cable }})
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="info-label">Created Date</label>
                                    <div class="info-value">{{ $project_amer->invoice->created_at->format('M d, Y H:i') }}</div>
                                </div>
                                @if ($project_amer->invoice->approved_by)
                                    <div class="info-group">
                                        <label class="info-label">Approved By</label>
                                        <div class="info-value">{{ $project_amer->invoice->approvedBy->name }}</div>
                                    </div>
                                    <div class="info-group">
                                        <label class="info-label">Approved Date</label>
                                        <div class="info-value">{{ $project_amer->invoice->approved_at?->format('M d, Y H:i') }}</div>
                                    </div>
                                @endif
                                @if ($project_amer->invoice->payment_file)
                                    <div class="info-group">
                                        <label class="info-label">Payment File</label>
                                        <div class="info-value">
                                            <a href="{{ asset('storage/' . $project_amer->invoice->payment_file) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View File
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($project_amer->invoice->notes)
                            <div class="info-group">
                                <label class="info-label">Notes</label>
                                <div class="info-value">{{ $project_amer->invoice->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('css')
    <style>
        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-white-20 {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .bg-purple-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .modern-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .info-value {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
        }

        .nav-tabs-line {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs-line .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
        }

        .nav-tabs-line .nav-link.active {
            color: #007bff;
            border-bottom-color: #007bff;
            background: none;
        }

        .modern-table thead th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }

        .modern-table tbody tr {
            border: none;
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 1rem;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .progress-sm {
            height: 6px;
        }

        .progress-lg {
            height: 12px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .payment-summary-card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .empty-state {
            padding: 2rem;
        }

        .badge-outline-primary {
            color: #007bff;
            border: 1px solid #007bff;
            background-color: transparent;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        /* Tab content animations */
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .nav-tabs-line .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .payment-summary-card .card-body h4 {
                font-size: 1.25rem;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Custom scrollbar for tables */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection

@section('js')
    <script></script>
@endsection

@extends('dashboard.layouts.master')

@section('title')
    Project Details - {{ $project->name }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('projects.index') }}">Projects</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ $project->name }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Project Header Card -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 class="text-white mb-2">{{ $project->name }}</h3>
                            <p class="text-white-50 mb-3">{{ $project->description }}</p>
                            <div class="d-flex flex-wrap" style="gap:1rem">
                                <span class="badge badge-light badge-lg">
                                    <i class="fas fa-calendar"></i>
                                    {{ $project->start_date->format('M d, Y') }} -
                                    {{ $project->end_date->format('M d, Y') }}
                                </span>
                                <span class="badge badge-light badge-lg">
                                    @if ($project->type === 'government')
                                        <i class="fas fa-landmark"></i>
                                    @elseif($project->type === 'commercial')
                                        <i class="fas fa-building"></i>
                                    @elseif($project->type === 'residential')
                                        <i class="fas fa-home"></i>
                                    @endif
                                    {{ $project->type }}
                                </span>

                                @php
                                                // تحديد لون البادج بناءً على حالة المشروع
                                                $statusClass = match ($project->status) {
                                                    'active' => 'info',
                                                    'completed' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };

                                                $statusIcons = match ($project->status) {
                                                    'active' => 'play',
                                                    'completed' => 'check',
                                                    'pending' => 'clock',
                                                    'cancelled' => 'times',
                                                    default => 'question',
                                                };
                                            @endphp

                                <span class="badge badge-{{ $statusClass }} badge-lg">
                                    <i class="fas fa-{{ $statusIcons }}"></i>
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="mb-3">
                                <h4 class="text-white mb-1">{{ number_format($project->project_cost) }} SAR</h4>
                                <small class="text-white-50">Total Project Cost</small>
                            </div>
                            <div class="btn-group">
                                @can('edit_project')
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit Project
                                    </a>
                                @endcan
                                <a href="{{ route('projects.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6">
            <div class="card overflow-hidden stats-card">
                <div class="card-body bg-success-gradient">
                    <div class="row">
                        <div class="col">
                            <h6 class="mb-3 tx-12 text-white">TEAM MEMBERS</h6>
                            <h3 class="mb-0 tx-28 text-white">{{ $project->projectTeams->count() }}</h3>
                            <small class="text-white-50">Active members</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-users tx-20 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card overflow-hidden stats-card">
                <div class="card-body bg-warning-gradient">
                    <div class="row">
                        <div class="col">
                            <h6 class="mb-3 tx-12 text-white">EQUIPMENT</h6>
                            <h3 class="mb-0 tx-28 text-white">{{ $project->projectEquipment->count() }}</h3>
                            <small class="text-white-50">Total equipment</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-tools tx-20 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card overflow-hidden stats-card">
                <div class="card-body bg-info-gradient">
                    <div class="row">
                        <div class="col">
                            <h6 class="mb-3 tx-12 text-white">PROGRESS</h6>
                            @php
                                $totalQty = $project->projectItems->sum('qty');
                                $executedQty = $project->projectItems->sum('executed_qty');
                                $progress = $totalQty > 0 ? round(($executedQty / $totalQty) * 100) : 0;
                            @endphp
                            <h3 class="mb-0 tx-28 text-white">{{ $progress }}%</h3>
                            <small class="text-white-50">Completed</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-chart-pie tx-20 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card overflow-hidden stats-card">
                <div class="card-body bg-purple-gradient">
                    <div class="row">
                        <div class="col">
                            <h6 class="mb-3 tx-12 text-white">PAYMENT</h6>
                            <h3 class="mb-0 tx-28 text-white">{{ round($project->payment_progress) }}%</h3>
                            <small class="text-white-50">{{ number_format($project->total_paid) }} SAR paid</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-money-bill-wave tx-20 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Project Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">PO Number</label>
                                <div class="info-value">{{ $project->po_num }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Type</label>
                                <div class="info-value">{{ $project->type }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Project Duration</label>
                                @php
                                    $remainingDays = now()->diffInDays($project->end_date, false); // باقي من اليوم لحد نهاية المشروع
                                @endphp
                                <div @class([
                                            'text-success info-value' => $remainingDays >= 40, // أخضر
                                            'text-warning info-value' => $remainingDays >= 20 && $remainingDays < 40, // أصفر
                                            'text-danger info-value' => $remainingDays < 20, // أحمر
                                        ])>{{ $remainingDays }} days
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Start Date</label>
                                <div class="info-value">{{ $project->start_date->format('M d, Y') }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">End Date</label>
                                <div class="info-value">{{ $project->end_date->format('M d, Y') }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Total Cost</label>
                                <div class="info-value text-success font-weight-bold">
                                    {{ number_format($project->project_cost) }} SAR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-body p-0">
                    <!-- Tab Navigation -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs nav-tabs-line nav-color-secondary" role="tablist">
                            <li class="nav-item">
                                <a href="#items" id="items-tab" data-toggle="tab" aria-expanded="false"
                                    class="nav-link active">
                                    <i class="fas fa-list" style="margin-right: 10px"></i>Project Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#team" id="team-tab" data-toggle="tab" aria-expanded="false"
                                    class="nav-link">
                                    <i class="fas fa-users" style="margin-right: 10px"></i>Team Members
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#equipment" id="equipment-tab" data-toggle="tab" aria-expanded="false"
                                    class="nav-link">
                                    <i class="fas fa-tools" style="margin-right: 10px"></i>Equipment
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#invoices" id="invoices-tab" data-toggle="tab" aria-expanded="false"
                                    class="nav-link">
                                    <i class="fas fa-file-invoice" style="margin-right: 10px"></i>Invoices
                                    <span class="badge badge-primary"
                                        style="margin-left: 10px">{{ $project->invoices->count() }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content p-4">
                        <!-- Project Items Tab -->
                        <div class="tab-pane active" id="items">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Section</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Received</th>
                                            <th>Executed</th>
                                            <th>Progress</th>
                                            <th>Expected Arrival</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($project->projectItems as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-outline-primary">{{ $item->section->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $item->sectionItem->name ?? 'N/A' }}</td>
                                                <td><strong>{{ $item->qty }}</strong></td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->received_qty == $item->qty ? 'success' : 'warning' }}">
                                                        {{ $item->received_qty }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->executed_qty == $item->received_qty ? 'success' : 'info' }}">
                                                        {{ $item->executed_qty }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    @php
                                                        $itemProgress =
                                                            $item->qty > 0
                                                                ? round(($item->executed_qty / $item->qty) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress progress-sm mb-1">
                                                        <div class="progress-bar {{ $itemProgress == 100 ? 'bg-success' : ($itemProgress >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                            style="width: {{ $itemProgress }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ $itemProgress }}%</small>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        {{ $item->expected_arrival ? $item->expected_arrival->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-list text-muted fa-3x mb-3"></i>
                                                        <p class="text-muted">No items found for this project</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Team Members Tab -->
                        <div class="tab-pane" id="team">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Member</th>
                                            <th>Phone</th>
                                             <th>Role</th>
                                            <th>Assigned Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($project->projectTeams->sortByDesc('is_lead') as $team)
                                            <tr class="{{ $team->is_lead ? 'table-warning' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm" style="margin-right: 10px">
                                    @if($team->is_lead)
                                        <span class="avatar-initial bg-warning text-dark">
                                            <i class="fas fa-crown"></i>
                                        </span>
                                    @else
                                        <span class="avatar-initial bg-primary">
                                            {{ substr($team->user->name ?? 'N', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-weight-bold">{{ $team->user->name ?? 'N/A' }}</span>
                                    @if($team->is_lead)
                                        <br>
                                        <small class="text-warning">
                                            <i class="fas fa-star"></i> Project Leader
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                                                <td>{{ $team->user->phone ?? 'N/A' }}</td>
                                                <td>
                            @if($team->is_lead)
                                <span class="badge badge-warning">
                                    <i class="fas fa-crown mr-1"></i>Leader
                                </span>
                            @else
                                <span class="badge badge-secondary">Member</span>
                            @endif
                        </td>
                                                <td>{{ $team->created_at ? $team->created_at->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">Active</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-users text-muted fa-3x mb-3"></i>
                                                        <p class="text-muted">No team members assigned to this project</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Equipment Tab -->
                        <div class="tab-pane" id="equipment">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Equipment</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th>Assigned Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($project->projectEquipment as $equipment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-tools text-primary"
                                                            style="margin-right: 5px"></i>
                                                        {{ $equipment->equipment->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td><strong>{{ $equipment->qty }}</strong></td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'available' => 'success',
                                                            'delivered' => 'info',
                                                            'unavailable' => 'warning',
                                                            'not_delivered' => 'danger',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge badge-{{ $statusColors[$equipment->status] ?? 'secondary' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $equipment->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $equipment->created_at ? $equipment->created_at->format('M d, Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-tools text-muted fa-3x mb-3"></i>
                                                        <p class="text-muted">No equipment assigned to this project</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Invoices Tab -->
                        <div class="tab-pane" id="invoices">
                            <!-- Payment Summary -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="payment-summary-card bg-primary">
                                        <div class="card-body text-center">
                                            <h4 class="text-white mb-1">{{ number_format($project->project_cost) }}</h4>
                                            <small class="text-white-50">Total Cost (SAR)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="payment-summary-card bg-success">
                                        <div class="card-body text-center">
                                            <h4 class="text-white mb-1">{{ number_format($project->total_paid) }}</h4>
                                            <small class="text-white-50">Paid (SAR)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="payment-summary-card bg-warning">
                                        <div class="card-body text-center">
                                            <h4 class="text-white mb-1">{{ number_format($project->remaining_amount) }}
                                            </h4>
                                            <small class="text-white-50">Remaining (SAR)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="payment-summary-card bg-info">
                                        <div class="card-body text-center">
                                            <h4 class="text-white mb-1">{{ round($project->payment_progress) }}%</h4>
                                            <small class="text-white-50">Progress</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Progress</span>
                                    <span>{{ round($project->payment_progress) }}%</span>
                                </div>
                                <div class="progress progress-lg">
                                    <div class="progress-bar bg-gradient-primary"
                                        style="width: {{ $project->payment_progress }}%"></div>
                                </div>
                            </div>

                            <!-- Invoices Table -->
                            <div class="table-responsive">
                                <table class="table table-hover modern-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Invoice Number</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Approved By</th>
                                            @if (auth()->user()->can('show_invoice') || auth()->user()->can('view_payment_invoice'))
                                                <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($project->invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </td>
                                                <td>
                                                    <span class="text-success font-weight-bold">
                                                        {{ number_format($invoice->amount) }} SAR
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                        ];
                                                        $statusIcons = [
                                                            'pending' => 'clock',
                                                            'approved' => 'check',
                                                            'rejected' => 'times',
                                                        ];
                                                    @endphp
                                                    <span class="badge badge-{{ $statusColors[$invoice->status] }}">
                                                        <i class="fas fa-{{ $statusIcons[$invoice->status] }} me-1"></i>
                                                        {{ ucfirst($invoice->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    {{ $invoice->approvedBy->name ?? '-' }}
                                                    @if ($invoice->approved_at)
                                                        <br><small
                                                            class="text-muted">{{ $invoice->approved_at->format('M d, Y') }}</small>
                                                    @endif
                                                </td>
                                                @if (auth()->user()->can('show_invoice') || auth()->user()->can('view_payment_invoice'))
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            @can('view_payment_invoice')
                                                                @if ($invoice->payment_file)
                                                                    <a href="{{ asset('storage/' . $invoice->payment_file) }}"
                                                                        target="_blank" class="btn btn-outline-primary btn-sm"
                                                                        title="View Payment File">
                                                                        <i class="fas fa-file-download"></i>
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                            @can('show_invoice')
                                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#invoiceModal{{ $invoice->id }}"
                                                                    title="View Details">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-file-invoice text-muted fa-3x mb-3"></i>
                                                        <p class="text-muted">No invoices found for this project</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modals -->
    @foreach ($project->invoices as $invoice)
        <div class="modal fade" id="invoiceModal{{ $invoice->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Invoice Details - {{ $invoice->invoice_number }}</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="info-label">Invoice Number</label>
                                    <div class="info-value">{{ $invoice->invoice_number }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Amount</label>
                                    <div class="info-value text-success">{{ number_format($invoice->amount) }} SAR</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">
                                        <span class="badge badge-{{ $statusColors[$invoice->status] }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="info-label">Created Date</label>
                                    <div class="info-value">{{ $invoice->created_at->format('M d, Y H:i') }}</div>
                                </div>
                                @if ($invoice->approved_by)
                                    <div class="info-group">
                                        <label class="info-label">Approved By</label>
                                        <div class="info-value">{{ $invoice->approvedBy->name }}</div>
                                    </div>
                                    <div class="info-group">
                                        <label class="info-label">Approved Date</label>
                                        <div class="info-value">{{ $invoice->approved_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($invoice->notes)
                            <div class="info-group">
                                <label class="info-label">Notes</label>
                                <div class="info-value">{{ $invoice->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
    <script>
        $(document).ready(function() {

            // Auto-refresh progress bars animation
            $('.progress-bar').each(function() {
                var $bar = $(this);
                var width = $bar.attr('style').match(/width:\s*(\d+)%/);
                if (width) {
                    $bar.css('width', '0%');
                    setTimeout(function() {
                        $bar.animate({
                            'width': width[1] + '%'
                        }, 1000);
                    }, 200);
                }
            });

            // Smooth scroll animation for cards
            $('.stats-card').each(function(index) {
                $(this).css('opacity', '0').delay(index * 100).animate({
                    opacity: 1
                }, 500);
            });

            // Tooltip initialization
            $('[title]').tooltip();

            // Table row click handler for better UX
            $('.modern-table tbody tr').on('click', function() {
                $(this).toggleClass('table-active');
            });

            // Search functionality (if needed)
            $('#tableSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.modern-table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        // Function to refresh project statistics (can be called via AJAX)
        function refreshProjectStats() {
            // This function can be implemented to fetch updated statistics
            // via AJAX without refreshing the entire page
            console.log('Refreshing project statistics...');
        }

        // Auto-refresh every 5 minutes (optional)
        setInterval(refreshProjectStats, 300000);
    </script>
@endsection

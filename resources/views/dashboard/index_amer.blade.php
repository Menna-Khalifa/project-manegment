{{-- dashboard new --}}

@extends('dashboard.layouts.master')

@section('css')
    <link href="{{ URL::asset('dashboard/assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('dashboard/assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card .tx-20 {
            font-size: 28px !important;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .progress-sm {
            height: 8px;
        }

        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 20px;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
        }

        .badge-pill {
            padding: 6px 12px;
            font-size: 13px;
        }

        .total-revenue label span {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
        }

        .total-revenue .progress {
            height: 8px;
        }

        .table th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            color: #6c757d;
            border-bottom: 2px solid #dee2e6;
        }

        .year-filter select {
            border-radius: 6px;
            border: 1px solid #e0e6ed;
            padding: 5px 15px;
        }

        .main-content-title {
            color: #1e2022;
            font-weight: 600;
        }

        .avatar-lg {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .avatar-lg i {
            font-size: 24px;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Welcome!</h2>
                <p class="mg-b-0">Project management and Reporting System Control Panel</p>
            </div>
        </div>
        <div class="main-dashboard-header-right">
            <div class="year-filter">
                <form action="{{ route('dashboard_amer') }}" method="GET" id="yearFilterForm">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 mr-2 tx-13 font-weight-semibold">Year:</label>
                        <select name="year" class="form-control form-control-sm" onchange="this.form.submit()"
                            style="width: 120px;">
                            @foreach ($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- الإحصائيات الرئيسية -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient stat-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">Total projects</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($stats['total_projects']) }}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Project In {{ $year }}</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-project-diagram text-white" style="font-size: 40px; opacity: 0.5;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient stat-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">Total Invoices</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($stats['total_invoices']) }}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Invoice In {{ $year }}</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-file-invoice text-white" style="font-size: 40px; opacity: 0.5;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient stat-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">Total Report</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($stats['total_reports']) }}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Report In {{ $year }}</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-file-alt text-white" style="font-size: 40px; opacity: 0.5;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient stat-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">Total Store</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($stats['total_stores']) }}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Registered Store</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-store text-white" style="font-size: 40px; opacity: 0.5;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
    </div>

    <!-- الإحصائيات المالية -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="avatar avatar-lg bg-primary-gradient text-white">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-1 tx-13 text-muted">Total Project Value</p>
                            <h4 class="mb-0 font-weight-bold">
                                {{ number_format($financialStats['total_projects_amount'], 2) }}</h4>
                            <small class="text-muted">SAR</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="avatar avatar-lg bg-success-gradient text-white">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-1 tx-13 text-muted">Paid Invoices</p>
                            <h4 class="mb-0 font-weight-bold">
                                {{ number_format($financialStats['paid_invoices_amount'], 2) }}</h4>
                            <small class="text-muted">SAR</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="avatar avatar-lg bg-warning-gradient text-white">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-1 tx-13 text-muted">Pending Invoices</p>
                            <h4 class="mb-0 font-weight-bold">
                                {{ number_format($financialStats['pending_invoices_amount'], 2) }}</h4>
                            <small class="text-muted">SAR</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="avatar avatar-lg bg-info-gradient text-white">
                                <i class="fas fa-receipt"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-1 tx-13 text-muted">Total Invoices</p>
                            <h4 class="mb-0 font-weight-bold">
                                {{ number_format($financialStats['total_invoices_amount'], 2) }}</h4>
                            <small class="text-muted">SAR</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المشاريع حسب الحالة والأولوية -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Projects by Status</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 text-muted mb-0">Overview of project statuses for the current year</p>
                </div>
                <div class="card-body">
                    <div class="total-revenue" style="position: relative !important;">
                        @php
                            $statusLabels = [
                                'new_order' => 'New Order',
                                'under_working' => 'Under Working',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'on_hold' => 'On Hold',
                            ];
                            $statusColors = [
                                'new_order' => 'primary',
                                'under_working' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'on_hold' => 'secondary',
                            ];
                            $totalProjects = array_sum($projectsByStatus) ?: 1;
                        @endphp
                        @foreach ($projectsByStatus as $status => $count)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <label><span
                                            class="bg-{{ $statusColors[$status] ?? 'primary' }}"></span>{{ $statusLabels[$status] ?? $status }}</label>
                                    <h5 class="mb-0 ml-1">{{ number_format($count) }}</h5>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $statusColors[$status] ?? 'primary' }}"
                                        style="width: {{ ($count / $totalProjects) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-5">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Projects by Priority</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 text-muted mb-0">Distribution of projects by priority level</p>
                </div>
                <div class="card-body">
                    <div class="total-revenue" style="position: relative !important;">
                        @php
                            $priorityLabels = [
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low',
                            ];
                            $priorityColors = [
                                'high' => 'danger',
                                'medium' => 'warning',
                                'low' => 'success',
                            ];
                            $totalPriority = array_sum($projectsByPriority) ?: 1;
                        @endphp
                        @foreach ($projectsByPriority as $priority => $count)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <label><span
                                            class="bg-{{ $priorityColors[$priority] ?? 'primary' }}"></span>{{ $priorityLabels[$priority] ?? $priority }}</label>
                                    <h5 class="mb-0 ml-1">{{ number_format($count) }}</h5>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $priorityColors[$priority] ?? 'primary' }}"
                                        style="width: {{ ($count / $totalPriority) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المشاريع حسب القسم والمنطقة -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Projects by Department</h3>
                    <p class="tx-12 text-muted mb-0">Distribution of projects across different departments</p>
                </div>
                <div class="card-body p-0">
                    @php
                        $deptLabels = [
                            'project' => 'Projects',
                            'facility' => 'Facilities',
                            'maintenance' => 'Maintenance',
                            'other' => 'Other',
                        ];
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($projectsByDept as $dept => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $deptLabels[$dept] ?? $dept }}</span>
                                <span class="badge badge-primary badge-pill">{{ number_format($count) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No data available</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Projects by Region</h3>
                    <p class="tx-12 text-muted mb-0">Geographical distribution of projects</p>
                </div>
                <div class="card-body p-0">
                    @php
                        $regionLabels = [
                            'western_province' => 'Western Province',
                            'central_province' => 'Central Province',
                            'eastern_province' => 'Eastern Province',
                            'general' => 'General',
                        ];
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($projectsByRegion as $region => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $regionLabels[$region] ?? $region }}</span>
                                <span class="badge badge-info badge-pill">{{ number_format($count) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No data available</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- أحدث المشاريع والفواتير -->
    <div class="row row-sm">
        <div class="col-xl-6">
            <div class="card p-3">
                <div class="card-header p-1">
                    <h4 class="card-title mb-2">Latest Projects</h4>
                    <span class="fs-12 text-muted mb-3">Last 5 projects added</span>
                </div>
                <div class="card-body p-0">
                <div class="table-responsive country-table">
                    <table class="table table-striped table-bordered mb-0 text-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-lg-25p">PO Number</th>
                                <th class="wd-lg-25p">Store</th>
                                <th class="wd-lg-25p">Amount</th>
                                <th class="wd-lg-25p">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProjects as $project)
                                <tr>
                                    <td>
                                        <span class="font-weight-semibold">{{ $project->po_num }}</span>
                                    </td>
                                    <td class="fw-medium">{{ $project->store->name ?? '-' }}</td>
                                    <td class="fw-medium">{{ number_format($project->amount, 2) }} SAR</td>
                                    <td class="fw-medium">
                                        @php
                                            $statusBadge = [
                                                'new_order' => 'badge-primary',
                                                'under_working' => 'badge-warning',
                                                'completed' => 'badge-success',
                                                'cancelled' => 'badge-danger',
                                                'on_hold' => 'badge-secondary',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $statusBadge[$project->request_status] ?? 'badge-primary' }}">
                                            {{ $statusLabels[$project->request_status] ?? $project->request_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No projects available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card p-3">
                <div class="card-header p-1">
                    <h4 class="card-title mb-2">Latest Invoices</h4>
                    <span class="fs-12 text-muted mb-3">Last 5 invoices issued</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Project</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                    <tr>
                                        <td><span class="font-weight-semibold">{{ $invoice->invoice_number }}</span></td>
                                        <td>{{ $invoice->projectAmer->po_num ?? '-' }}</td>
                                        <td>{{ number_format($invoice->amount, 2) }} SAR</td>
                                        <td>
                                            @php
                                                $invoiceStatusBadge = [
                                                    'paid' => 'badge-success',
                                                    'pending' => 'badge-warning',
                                                    'canceled' => 'badge-danger',
                                                    'invoice_issuse' => 'badge-info',
                                                    'ready_of_invoicing' => 'badge-primary',
                                                    'submitted' => 'badge-secondary',
                                                ];
                                                $invoiceStatusLabels = [
                                                    'paid' => 'Paid',
                                                    'pending' => 'Pending',
                                                    'canceled' => 'Canceled',
                                                    'invoice_issuse' => 'Invoice Issue',
                                                    'ready_of_invoicing' => 'Ready of Invoicing',
                                                    'submitted' => 'Submitted',
                                                ];
                                            @endphp
                                            <span
                                                class="badge {{ $invoiceStatusBadge[$invoice->status] ?? 'badge-primary' }}">
                                                {{ $invoiceStatusLabels[$invoice->status] ?? $invoice->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No invoices available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أعلى المتاجر -->
    <div class="row row-sm">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Top Stores (Number of Projects)</h3>
                    <p class="tx-12 text-muted mb-0">Top stores by number of projects</p>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topStoresByProjects as $store)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $store->name }}</h6>
                                    <small class="text-muted">{{ $store->brand->name ?? '-' }}</small>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $store->project_amers_count }}
                                    مشروع</span>
                            </div>
                        @empty
                            <p class="text-center text-muted py-4">No stores available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Top Stores (Total Project Amount)</h3>
                    <p class="tx-12 text-muted mb-0">Top stores by total project amount</p>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topStoresByAmount as $store)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $store->name }}</h6>
                                    <small class="text-muted">{{ $store->brand->name ?? '-' }}</small>
                                </div>
                                <span class="badge badge-success badge-pill">{{ number_format($store->total_amount, 2) }}
                                    SAR</span>
                            </div>
                        @empty
                            <p class="text-center text-muted py-4">No stores available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- التقارير حسب النوع -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Reports by Type</h3>
                    <p class="tx-12 text-muted mb-0">Distribution of reports by different types</p>
                </div>
                <div class="card-body">
                    @php
                        $reportTypeLabels = [
                            'start_up_report' => 'Startup Report',
                            'work_completed' => 'Work Completed Report',
                            'sites_refer_report' => 'Sites Refer Report',
                        ];
                        $reportTypeColors = [
                            'start_up_report' => 'primary',
                            'work_completed' => 'success',
                            'sites_refer_report' => 'info',
                        ];
                    @endphp
                    <div class="row">
                        @forelse($reportsByType as $type => $count)
                            <div class="col-md-4 col-sm-6">
                                <div class="card bg-{{ $reportTypeColors[$type] ?? 'primary' }}-gradient mb-3">
                                    <div class="card-body text-center text-white">
                                        <h3 class="mb-2 font-weight-bold">{{ number_format($count) }}</h3>
                                        <p class="mb-0">{{ $reportTypeLabels[$type] ?? $type }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">Not Found Data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- الرسوم البيانية -->
    <div class="row row-sm">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Monthly Projects</h4>
                    <p class="tx-12 text-muted mb-0">Number of projects for each month in {{ $year }}</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyProjectsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Monthly Financial Value</h4>
                    <p class="tx-12 text-muted mb-0">Total financial value of projects for each month</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyFinancialChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Projects Status Distribution</h4>
                    <p class="tx-12 text-muted mb-0">Percentage of each project status out of total projects</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="projectsStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Reports by Type Distribution</h4>
                    <p class="tx-12 text-muted mb-0">Distribution of reports by different types</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="reportsByTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('dashboard/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>

    <script>
        $(function() {
            'use strict';

            // البيانات من الـ Controller
            var currentYear = {{ $year }};
            var monthlyProjectsData = @json($monthlyProjects);
            var monthlyInvoicesData = @json($monthlyInvoices);
            var projectsByStatus = @json($projectsByStatus);
            var reportsByType = @json($reportsByType);

            // أسماء الأشهر بالعربية
            var monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            // إعداد بيانات المشاريع الشهرية
            var projectsMonthLabels = [];
            var projectsMonthCounts = [];
            for (var i = 1; i <= 12; i++) {
                projectsMonthLabels.push(monthNames[i - 1]);
                projectsMonthCounts.push(monthlyProjectsData[i] ? monthlyProjectsData[i].count : 0);
            }

            // الرسم البياني للمشاريع الشهرية
            if ($('#monthlyProjectsChart').length) {
                var ctx1 = document.getElementById('monthlyProjectsChart').getContext('2d');
                var monthlyProjectsChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: projectsMonthLabels,
                        datasets: [{
                            label: 'Number of Projects',
                            data: projectsMonthCounts,
                            backgroundColor: 'rgba(88, 103, 221, 0.8)',
                            borderColor: 'rgba(88, 103, 221, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            labels: {
                                fontFamily: 'Cairo, sans-serif'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: 'Cairo, sans-serif'
                                },
                                gridLines: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontFamily: 'Cairo, sans-serif'
                                },
                                gridLines: {
                                    display: false
                                }
                            }]
                        }
                    }
                });
            }

            // إعداد بيانات القيمة المالية الشهرية
            var financialMonthLabels = [];
            var financialMonthAmounts = [];
            for (var i = 1; i <= 12; i++) {
                financialMonthLabels.push(monthNames[i - 1]);
                financialMonthAmounts.push(monthlyProjectsData[i] ? parseFloat(monthlyProjectsData[i]
                    .total_amount) : 0);
            }

            // الرسم البياني للقيمة المالية الشهرية
            if ($('#monthlyFinancialChart').length) {
                var ctx2 = document.getElementById('monthlyFinancialChart').getContext('2d');
                var monthlyFinancialChart = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: financialMonthLabels,
                        datasets: [{
                            label: 'Total Financial Amount (SAR)',
                            data: financialMonthAmounts,
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            labels: {
                                fontFamily: 'Cairo, sans-serif'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: 'Cairo, sans-serif',
                                    callback: function(value) {
                                        return value.toLocaleString('ar-SA');
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontFamily: 'Cairo, sans-serif'
                                },
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex].label + ': ' +
                                        tooltipItem.yLabel.toLocaleString('ar-SA') + ' SAR';
                                }
                            }
                        }
                    }
                });
            }

            // إعداد بيانات حالة المشاريع
            var statusLabels = {
                'new_order': 'New Order',
                'under_working': 'Under Working',
                'completed': 'Completed',
                'cancelled': 'Cancelled',
                'on_hold': 'On Hold'
            };
            var statusColors = {
                'new_order': 'rgba(88, 103, 221, 0.8)',
                'under_working': 'rgba(255, 193, 7, 0.8)',
                'completed': 'rgba(40, 167, 69, 0.8)',
                'cancelled': 'rgba(220, 53, 69, 0.8)',
                'on_hold': 'rgba(108, 117, 125, 0.8)'
            };

            var statusChartLabels = [];
            var statusChartData = [];
            var statusChartColors = [];

            for (var status in projectsByStatus) {
                statusChartLabels.push(statusLabels[status] || status);
                statusChartData.push(projectsByStatus[status]);
                statusChartColors.push(statusColors[status] || 'rgba(88, 103, 221, 0.8)');
            }

            // الرسم البياني لحالة المشاريع
            if ($('#projectsStatusChart').length) {
                var ctx3 = document.getElementById('projectsStatusChart').getContext('2d');
                var projectsStatusChart = new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: statusChartLabels,
                        datasets: [{
                            data: statusChartData,
                            backgroundColor: statusChartColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontFamily: 'Cairo, sans-serif',
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.labels[tooltipItem.index];
                                    var value = data.datasets[0].data[tooltipItem.index];
                                    var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    var percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                });
            }

            // إعداد بيانات أنواع التقارير
            var reportTypeLabels = {
                'start_up_report': 'Startup Report',
                'work_completed': 'Work Completed Report',
                'sites_refer_report': 'Sites Refer Report'
            };
            var reportTypeColors = {
                'start_up_report': 'rgba(88, 103, 221, 0.8)',
                'work_completed': 'rgba(40, 167, 69, 0.8)',
                'sites_refer_report': 'rgba(23, 162, 184, 0.8)'
            };

            var reportChartLabels = [];
            var reportChartData = [];
            var reportChartColors = [];

            for (var type in reportsByType) {
                reportChartLabels.push(reportTypeLabels[type] || type);
                reportChartData.push(reportsByType[type]);
                reportChartColors.push(reportTypeColors[type] || 'rgba(88, 103, 221, 0.8)');
            }

            // الرسم البياني لأنواع التقارير
            if ($('#reportsByTypeChart').length) {
                var ctx4 = document.getElementById('reportsByTypeChart').getContext('2d');
                var reportsByTypeChart = new Chart(ctx4, {
                    type: 'pie',
                    data: {
                        labels: reportChartLabels,
                        datasets: [{
                            data: reportChartData,
                            backgroundColor: reportChartColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontFamily: 'Cairo, sans-serif',
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.labels[tooltipItem.index];
                                    var value = data.datasets[0].data[tooltipItem.index];
                                    var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    var percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                });
            }

            // Sparkline scripts for stat cards
            if ($.fn.sparkline) {
                if ($('#compositeline').length) {
                    $('#compositeline').sparkline('html', {
                        width: '100%',
                        height: 40,
                        lineColor: 'rgba(255,255,255,0.5)',
                        fillColor: 'rgba(255,255,255,0.2)',
                        spotColor: '#fff',
                        minSpotColor: '#fff',
                        maxSpotColor: '#fff',
                        highlightSpotColor: '#fff',
                        highlightLineColor: '#fff'
                    });
                }

                if ($('#compositeline2').length) {
                    $('#compositeline2').sparkline('html', {
                        width: '100%',
                        height: 40,
                        lineColor: 'rgba(255,255,255,0.5)',
                        fillColor: 'rgba(255,255,255,0.2)',
                        spotColor: '#fff',
                        minSpotColor: '#fff',
                        maxSpotColor: '#fff',
                        highlightSpotColor: '#fff',
                        highlightLineColor: '#fff'
                    });
                }

                if ($('#compositeline3').length) {
                    $('#compositeline3').sparkline('html', {
                        width: '100%',
                        height: 40,
                        lineColor: 'rgba(255,255,255,0.5)',
                        fillColor: 'rgba(255,255,255,0.2)',
                        spotColor: '#fff',
                        minSpotColor: '#fff',
                        maxSpotColor: '#fff',
                        highlightSpotColor: '#fff',
                        highlightLineColor: '#fff'
                    });
                }

                if ($('#compositeline4').length) {
                    $('#compositeline4').sparkline('html', {
                        width: '100%',
                        height: 40,
                        lineColor: 'rgba(255,255,255,0.5)',
                        fillColor: 'rgba(255,255,255,0.2)',
                        spotColor: '#fff',
                        minSpotColor: '#fff',
                        maxSpotColor: '#fff',
                        highlightSpotColor: '#fff',
                        highlightLineColor: '#fff'
                    });
                }
            }

        });
    </script>
@endsection

<!-- resources/views/dashboard/reports/index.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Reports List
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Reports List
    </li>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mg-b-0">All Reports</h4>
                    @can('add_report')
                    <a class="btn btn-primary" href="{{ route('reports.create') }}">
                        <i class="las la-file-alt"></i> Add Report
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table key-buttons text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Report Type</th>
                                <th>Report Date</th>
                                <th>Store Name</th>
                                <th>City</th>
                                <th>Project</th>
                                <th>Created By</th>
                                @if (auth()->user()->can('show_report') || auth()->user()->can('edit_report') || auth()->user()->can('download_report') || auth()->user()->can('delete_report'))
                                <th>Processes</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($report->report_type === 'start_up_report')
                                            <span class="badge badge-primary">Start Up Report</span>
                                        @elseif ($report->report_type === 'work_completed')
                                            <span class="badge badge-success">Work Completed</span>
                                        @elseif ($report->report_type === 'sites_refer_report')
                                            <span class="badge badge-info">Sites Refer Report</span>
                                        @endif
                                    </td>
                                    <td>{{ $report->report_date->format('d/m/Y') }}</td>
                                    <td><strong>{{ $report->store->name ?? '—' }}</strong></td>
                                    <td>{{ $report->store->city ?? '—' }}</td>
                                    <td>{{ $report->projectAmer->po_num ?? '—' }}</td>
                                    <td>{{ $report->creator->name ?? '—' }}</td>
                                    @if (auth()->user()->can('show_report') || auth()->user()->can('edit_report') || auth()->user()->can('download_report') || auth()->user()->can('delete_report'))
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">
                                                Processes&nbsp;&nbsp;<i class="fas fa-caret-down ml-1"></i>
                                            </button>
                                            <div class="dropdown-menu tx-13">
                                                @can('show_report')
                                                <a class="dropdown-item" href="{{ route('reports.show', $report->id) }}">
                                                    <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                </a>
                                                @endcan
                                                @can('edit_report')
                                                <a class="dropdown-item" href="{{ route('reports.edit', $report->id) }}">
                                                    <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                </a>
                                                @endcan
                                                @can('download_report')
                                                <a class="dropdown-item" href="{{ route('reports.download-pdf', $report->id) }}">
                                                    <i class="text-success fas fa-file-pdf"></i>&nbsp;&nbsp;Download PDF
                                                </a>
                                                @endcan
                                                @can('delete_report')
                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this report?')">
                                                        <i class="text-danger fas fa-trash"></i>&nbsp;&nbsp;Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No reports found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
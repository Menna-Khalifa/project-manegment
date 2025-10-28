@extends('dashboard.layouts.master')

@section('title')
    Projects List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('projects.index') }}">Projects</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Projects List
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <!-- Filter Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('projects.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Type</label>
                                <select class="form-control" name="type">
                                    <option value="">All Types</option>
                                    <option value="government" {{ request('type') === 'government' ? 'selected' : '' }}>
                                        Government</option>
                                    <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>
                                        Commercial</option>
                                    <option value="residential" {{ request('type') === 'residential' ? 'selected' : '' }}>
                                        Residential</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Start Date</label>
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label>End Date</label>
                                <input type="date" class="form-control" name="end_date"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>PO Number</label>
                                <input type="text" class="form-control" name="po_num" value="{{ request('po_num') }}"
                                    placeholder="Search by PO number">
                            </div>
                            <div class="col-md-3">
                                <label>Search</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Search in name or description">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-block">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_project')
                            <a class="btn btn-primary" href="{{ route('projects.create') }}">
                                <i class="las la-plus"></i>
                                Add Project</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PO Number</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Project Duration</th>
                                    <th>Status</th>
                                    <th>Project Cost</th>
                                    <th>Payments</th>
                                    <th>Completion percentage</th>
                                    @if (auth()->user()->can('edit_project') || auth()->user()->can('show_project') || auth()->user()->can('delete_project'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $key => $project)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @can('show_project')
                                                <a href="{{ route('projects.show', $project->id) }}">
                                                    {{ $project->po_num ?? __('general.not_found') }}
                                                </a>
                                            @else
                                                {{ $project->po_num ?? __('general.not_found') }}
                                            @endcan
                                        </td>
                                        <td>{{ $project->type ?? __('general.not_found') }}</td>
                                        <td>{{ $project->name ?? __('general.not_found') }}</td>
                                        <td>{{ $project->start_date ? $project->start_date->format('Y-m-d') : __('general.not_found') }}
                                        </td>
                                        <td>{{ $project->end_date ? $project->end_date->format('Y-m-d') : __('general.not_found') }}
                                        </td>
                                        @php
                                            $remainingDays = now()->diffInDays($project->end_date, false); // باقي من اليوم لحد نهاية المشروع
                                        @endphp

                                        <td @class([
                                            'text-success' => $remainingDays >= 40, // أخضر
                                            'text-warning' => $remainingDays >= 20 && $remainingDays < 40, // أصفر
                                            'text-danger' => $remainingDays < 20, // أحمر
                                        ])>
                                            {{ $remainingDays }} days
                                        </td>
                                        <td>
                                            @php
                                                // تحديد لون البادج بناءً على حالة المشروع
                                                $statusClass = match ($project->status) {
                                                    'active' => 'primary',
                                                    'completed' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($project->project_cost) }}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                Pending: {{ number_format($project->total_payment_pending) }}
                                            </span>
                                            <span class="badge bg-info">
                                                Remaining: {{ number_format($project->remaining_amount) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped
                                                    {{ $project->completion_percentage == 100 ? 'bg-success' : ($project->completion_percentage >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                     role="progressbar" style="width: {{ $project->completion_percentage }}%">
                                                    {{ $project->completion_percentage }}%
                                                </div>
                                            </div>
                                        </td>


                                        @if (auth()->user()->can('edit_project') || auth()->user()->can('show_project') || auth()->user()->can('delete_project'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_project')
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.show', $project->id) }}">
                                                                <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                            </a>
                                                        @endcan
                                                        @can('edit_project')
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.edit', $project->id) }}">
                                                                <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('delete_project')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $project->id }}"
                                                                title="Delete Project">
                                                                <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $project->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Project</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('projects.destroy', $project->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this project?</p><br>
                                                        <input type="hidden" name="project_id"
                                                            value="{{ $project->id }}">
                                                        <input class="form-control" name="project_name"
                                                            value="{{ $project->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
    </div>
    <!-- /row -->
    </div>
    <!-- /row -->
@endsection

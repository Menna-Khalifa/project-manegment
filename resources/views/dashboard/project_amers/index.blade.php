@extends('dashboard.layouts.master')

@section('title')
    ProjectAmer List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_amers.index') }}">ProjectAmer</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        ProjectAmer List
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
                    <form method="GET" action="{{ route('project_amers.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Status</label>
                                <select class="form-control" name="request_status">
                                    <option value="">All Statuses</option>
                                    <option value="new_order"
                                        {{ request('request_status') === 'new_order' ? 'selected' : '' }}>New Order</option>
                                    <option value="under_working"
                                        {{ request('request_status') === 'under_working' ? 'selected' : '' }}>Under Working
                                    </option>
                                    <option value="completed"
                                        {{ request('request_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ request('request_status') === 'on_hold' ? 'selected' : '' }}>
                                        On Hold</option>
                                    <option value="cancelled"
                                        {{ request('request_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Region</label>
                                <select class="form-control" name="region">
                                    <option value="">All Regions</option>
                                    <option value="western_province"
                                        {{ request('region') === 'western_province' ? 'selected' : '' }}>Western Province
                                    </option>
                                    <option value="central_province"
                                        {{ request('region') === 'central_province' ? 'selected' : '' }}>Central Province
                                    </option>
                                    <option value="eastern_province"
                                        {{ request('region') === 'eastern_province' ? 'selected' : '' }}>Eastern Province
                                    </option>
                                    <option value="general" {{ request('region') === 'general' ? 'selected' : '' }}>General
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Department</label>
                                <select class="form-control" name="dept">
                                    <option value="">All Departments</option>
                                    <option value="project" {{ request('dept') === 'project' ? 'selected' : '' }}>Project
                                    </option>
                                    <option value="facility" {{ request('dept') === 'facility' ? 'selected' : '' }}>
                                        Facility</option>
                                    <option value="maintenance" {{ request('dept') === 'maintenance' ? 'selected' : '' }}>
                                        Maintenance</option>
                                    <option value="other" {{ request('dept') === 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Priority</label>
                                <select class="form-control" name="priority">
                                    <option value="">All Priorities</option>
                                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>PO Number</label>
                                <input type="text" class="form-control" name="po_num" value="{{ request('po_num') }}"
                                    placeholder="Search by PO number">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <a href="{{ route('project_amers.index') }}"
                                        class="btn btn-secondary btn-block">Clear</a>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_project_amers')
                            <a class="btn btn-primary" href="{{ route('project_amers.create') }}">
                                <i class="las la-plus"></i>
                                Add ProjectAmer</a>
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
                                    <th>Department</th>
                                    <th>Region</th>
                                    <th>Store</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>PO File</th>
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
                                            @can('show_project_amers')
                                                <a href="{{ route('project_amers.show', $project->id) }}">
                                                    {{ $project->po_num ?? __('general.not_found') }}
                                                </a>
                                            @else
                                                {{ $project->po_num ?? __('general.not_found') }}
                                            @endcan
                                        </td>
                                        <td>{{ $project->dept ?? __('general.not_found') }}</td>
                                        <td>{{ $project->region ?? __('general.not_found') }}</td>
                                        <td>{{ $project->store->name ?? __('general.not_found') }}</td>
                                        <td>{{ $project->user->name ?? __('general.not_found') }}</td>
                                        <td>{{ $project->date
                                            ? (is_string($project->date)
                                                ? $project->date
                                                : $project->date->format('Y-m-d'))
                                            : __('general.not_found') }}
                                        </td>
                                        <td>
                                            @php
                                                $priorityClass = match ($project->priority) {
                                                    'high' => 'danger',
                                                    'medium' => 'warning',
                                                    'low' => 'success',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span
                                                class="badge badge-{{ $priorityClass }}">{{ ucfirst($project->priority) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match ($project->request_status) {
                                                    'new_order' => 'primary',
                                                    'under_working' => 'info',
                                                    'completed' => 'success',
                                                    'on_hold' => 'warning',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span
                                                class="badge badge-{{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($project->request_status)) }}</span>
                                        </td>
                                        <td>{{ number_format($project->amount, 2) }}</td>
                                        <td>
                                            @if ($project->po_file)
                                                <a href="{{ asset('storage/' . $project->po_file) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info">View</a>
                                            @else
                                                -
                                            @endif
                                        </td>


                                        @if (auth()->user()->can('edit_project_amers') || auth()->user()->can('show_project_amers') || auth()->user()->can('delete_project_amers'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('download_project_amers')
                                                        <a href="{{ route('project_amers.download_service_completion', $project->id) }}"
                                                            class="dropdown-item">
                                                            <i class="text-warning fa fa-download"></i>&nbsp;&nbsp; تحميل Service Completion
                                                        </a>
                                                        @endcan
                                                        @can('show_project_amers')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project_amers.show', $project->id) }}">
                                                                <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                            </a>
                                                        @endcan
                                                        @can('edit_project_amers')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project_amers.edit', $project->id) }}">
                                                                <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('delete_project_amers')
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
                                                    <h6 class="modal-title">Delete ProjectAmer</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project_amers.destroy', $project->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this ProjectAmer?</p><br>
                                                        <input type="hidden" name="project_id"
                                                            value="{{ $project->id }}">
                                                        <input class="form-control" name="po_num"
                                                            value="{{ $project->po_num }}" type="text" readonly>
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

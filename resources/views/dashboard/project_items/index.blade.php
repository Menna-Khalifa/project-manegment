@extends('dashboard.layouts.master')

@section('title')
    Project Items List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-items.index') }}">Project Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Project Items List
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
                    <form method="GET" action="{{ route('project-items.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Project</label>
                                <select class="form-control" name="project_id">
                                    <option value="">All Projects</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Section</label>
                                <select class="form-control" name="section_id">
                                    <option value="">All Sections</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                                {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Arrival From</label>
                                <input type="date" class="form-control" name="arrival_from" value="{{ request('arrival_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Arrival To</label>
                                <input type="date" class="form-control" name="arrival_to" value="{{ request('arrival_to') }}">
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
                                <label>Search</label>
                                <input type="text" class="form-control" name="search"
                                       value="{{ request('search') }}" placeholder="Search in project name">
                            </div>
                            <div class="col-md-2">
                                <label>Pending Delivery</label>
                                <select class="form-control" name="pending_delivery">
                                    <option value="">All</option>
                                    <option value="1" {{ request('pending_delivery') === '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Pending Execution</label>
                                <select class="form-control" name="pending_execution">
                                    <option value="">All</option>
                                    <option value="1" {{ request('pending_execution') === '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <a href="{{ route('project-items.index') }}" class="btn btn-secondary btn-block">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_project_item')
                            <a class="btn btn-primary" href="{{ route('project-items.create') }}">
                                <i class="las la-plus"></i>
                                Add Project Item</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Section</th>
                                    <th>Section Item</th>
                                    <th>Quantity</th>
                                    <th>Received</th>
                                    <th>Executed</th>
                                    <th>Expected Arrival</th>
                                    <th>Progress</th>
                                    @if (auth()->user()->can('show_project_item') ||
                                    auth()->user()->can('edit_project_item') ||
                                    auth()->user()->can('edit_received_project_item') ||
                                    auth()->user()->can('edit_executed_project_item') ||
                                         auth()->user()->can('delete_project_item'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectItems as $key => $item)
                                    <tr>
                                           <td>
                                            {{ ($projectItems->currentPage() - 1) * $projectItems->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $item->project->name ?? __('general.not_found') }}</td>
                                        <td>{{ $item->section->name ?? __('general.not_found') }}</td>
                                        <td>{{ $item->sectionItem->name ?? __('general.not_found') }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->received_qty == $item->qty ? 'success' : 'warning' }}">
                                                {{ $item->received_qty }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->executed_qty == $item->received_qty ? 'success' : 'info' }}">
                                                {{ $item->executed_qty }}
                                            </span>
                                        </td>
                                        <td>{{ $item->expected_arrival ? $item->expected_arrival->format('Y-m-d') : __('general.not_found') }}</td>
                                        <td>
                                            @php
                                                $progress = $item->qty > 0 ? round(($item->executed_qty / $item->qty) * 100) : 0;
                                            @endphp
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped
                                                    {{ $progress == 100 ? 'bg-success' : ($progress >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                     role="progressbar" style="width: {{ $progress }}%">
                                                    {{ $progress }}%
                                                </div>
                                            </div>
                                        </td>

                                        @if (auth()->user()->can('show_project_item') ||
                                            auth()->user()->can('edit_project_item') ||
                                            auth()->user()->can('edit_received_project_item') ||
                                            auth()->user()->can('edit_executed_project_item') ||
                                             auth()->user()->can('delete_project_item'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_project_item')
                                                            <a class="dropdown-item" href="{{ route('project-items.show', $item->id) }}">
                                                                <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                            </a>
                                                        @endcan
                                                        @can('edit_project_item')
                                                            <a class="dropdown-item" href="{{ route('project-items.edit', $item->id) }}">
                                                                <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('edit_received_project_item')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#updateReceived-{{ $item->id }}">
                                                                <i class="text-success fas fa-truck"></i>&nbsp;&nbsp;Update Received
                                                            </a>
                                                        @endcan
                                                        @can('edit_executed_project_item')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#updateExecuted-{{ $item->id }}">
                                                                <i class="text-warning fas fa-cog"></i>&nbsp;&nbsp;Update Executed
                                                            </a>
                                                        @endcan
                                                        @can('delete_project_item')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $item->id }}"
                                                                title="Delete Project Item">
                                                                <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Update Received Quantity Modal -->
                                    <div class="modal" id="updateReceived-{{ $item->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Received Quantity</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-items.update-received-qty', $item->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Current Received Quantity: {{ $item->received_qty }}</label>
                                                            <label>Total Quantity: {{ $item->qty }}</label>
                                                            <input type="number" class="form-control" name="received_qty"
                                                                   value="{{ $item->received_qty }}"
                                                                   min="0" max="{{ $item->qty }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Update Executed Quantity Modal -->
                                    <div class="modal" id="updateExecuted-{{ $item->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Executed Quantity</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-items.update-executed-qty', $item->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Current Executed Quantity: {{ $item->executed_qty }}</label>
                                                            <label>Received Quantity: {{ $item->received_qty }}</label>
                                                            <input type="number" class="form-control" name="executed_qty"
                                                                   value="{{ $item->executed_qty }}"
                                                                   min="0" max="{{ $item->received_qty }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $item->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Project Item</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-items.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this project item?</p><br>
                                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                        <input class="form-control" name="project_name"
                                                            value="{{ $item->project->name }} - {{ $item->sectionItem->name ?? 'Unknown Item' }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $projectItems->appends(request()->query())->links('component.pagination', ['items' => $projectItems]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

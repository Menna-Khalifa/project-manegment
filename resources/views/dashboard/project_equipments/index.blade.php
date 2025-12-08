@extends('dashboard.layouts.master')

@section('title')
    Project Equipment List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-equipments.index') }}">Project Equipment</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Project Equipment List
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
                    <form method="GET" action="{{ route('project-equipments.index') }}">
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
                                <label>Equipment</label>
                                <select class="form-control" name="equipment_id">
                                    <option value="">All Equipment</option>
                                    @foreach ($equipment as $eq)
                                        <option value="{{ $eq->id }}"
                                            {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>
                                            {{ $eq->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>
                                        Available</option>
                                    <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>
                                        Unavailable</option>
                                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    <a href="{{ route('project-equipments.index') }}"
                                        class="btn btn-secondary btn-block mt-1">Clear</a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Search</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Search in project or equipment name">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Assignment Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Bulk Equipment Assignment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('project-equipments.bulk-assign') }}" id="bulkAssignForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Select Project</label>
                                <select class="form-control" name="project_id" id="bulkProjectSelect" required>
                                    <option value="">Choose Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="equipmentRows">
                            <div class="equipment-row row mb-2">
                                <div class="col-md-4">
                                    <label>Equipment</label>
                                    <select class="form-control equipment-select" name="equipment_data[0][equipment_id]"
                                        required>
                                        <option value="">Select Equipment</option>
                                        @foreach ($equipment as $eq)
                                            <option value="{{ $eq->id }}">{{ $eq->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control" name="equipment_data[0][qty]" min="1"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select class="form-control" name="equipment_data[0][status]" required>
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                        <option value="delivered">Delivered</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-danger btn-sm remove-row"
                                            style="display:none;">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @can('add_project_equipment')
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info" id="addEquipmentRow">Add Another
                                        Equipment</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-success">Assign Equipment</button>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_project_equipment')
                            <a class="btn btn-primary" href="{{ route('project-equipments.create') }}">
                                <i class="las la-plus"></i>
                                Add Equipment</a>
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
                                    <th>Equipment</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Assigned Date</th>
                                    @if (auth()->user()->can('show_project_equipment') ||
                                        auth()->user()->can('edit_project_equipment') ||
                                        auth()->user()->can('edit_status_project_equipment') ||
                                     auth()->user()->can('delete_project_equipment'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectEquipment as $key => $eq)
                                    <tr>
                                           <td>
                                            {{ ($projectEquipment->currentPage() - 1) * $projectEquipment->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $eq->project->name ?? __('general.not_found') }}</td>
                                        <td>{{ $eq->equipment->name ?? __('general.not_found') }}</td>
                                        <td>{{ $eq->qty }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $eq->status === 'available' ? 'success' : ($eq->status === 'delivered' ? 'info' : 'warning') }}">
                                                {{ ucfirst($eq->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $eq->created_at ? $eq->created_at->format('Y-m-d') : __('general.not_found') }}
                                        </td>

                                        @if (auth()->user()->can('show_project_equipment') ||
                                        auth()->user()->can('edit_project_equipment') ||
                                        auth()->user()->can('edit_status_project_equipment') ||
                                     auth()->user()->can('delete_project_equipment'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_project_equipment')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project-equipments.show', $eq->id) }}">
                                                                <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                            </a>
                                                        @endcan
                                                        @can('edit_project_equipment')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project-equipments.edit', $eq->id) }}">
                                                                <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('edit_status_project_equipment')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#updateStatus-{{ $eq->id }}">
                                                                <i class="text-warning fas fa-toggle-on"></i>&nbsp;&nbsp;Update
                                                                Status
                                                            </a>
                                                        @endcan
                                                        @can('delete_project_equipment')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $eq->id }}"
                                                                title="Remove Equipment">
                                                                <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Remove
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Update Status Modal -->
                                    <div class="modal" id="updateStatus-{{ $eq->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Equipment Status</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-equipments.update-status', $eq->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Current Status:
                                                                <span
                                                                    class="badge badge-{{ $eq->status === 'available' ? 'success' : ($eq->status === 'delivered' ? 'info' : 'warning') }}">
                                                                    {{ ucfirst($eq->status) }}
                                                                </span>
                                                            </label>
                                                            <select class="form-control" name="status" required>
                                                                <option value="available"
                                                                    {{ $eq->status === 'available' ? 'selected' : '' }}>
                                                                    Available</option>
                                                                <option value="unavailable"
                                                                    {{ $eq->status === 'unavailable' ? 'selected' : '' }}>
                                                                    Unavailable</option>
                                                                <option value="delivered"
                                                                    {{ $eq->status === 'delivered' ? 'selected' : '' }}>
                                                                    Delivered</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Update
                                                            Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $eq->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Remove Equipment</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-equipments.destroy', $eq->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to remove this equipment from the project?
                                                        </p><br>
                                                        <div class="alert alert-info">
                                                            <strong>Project:</strong> {{ $eq->project->name }}<br>
                                                            <strong>Equipment:</strong> {{ $eq->equipment->name }}<br>
                                                            <strong>Quantity:</strong> {{ $eq->qty }}
                                                        </div>
                                                        <input type="hidden" name="equipment_id"
                                                            value="{{ $eq->id }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Remove</button>
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
                        {{ $projectEquipment->appends(request()->query())->links('component.pagination', ['items' => $projectEquipment]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            let rowCount = 1;

            // Add new equipment row
            $('#addEquipmentRow').click(function() {
                const newRow = `
                    <div class="equipment-row row mb-2">
                        <div class="col-md-4">
                            <select class="form-control equipment-select" name="equipment_data[${rowCount}][equipment_id]" required>
                                <option value="">Select Equipment</option>
                                @foreach ($equipment as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="equipment_data[${rowCount}][qty]" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="equipment_data[${rowCount}][status]" required>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                        </div>
                    </div>
                `;
                $('#equipmentRows').append(newRow);
                rowCount++;
                updateRemoveButtons();
            });

            // Remove equipment row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.equipment-row').remove();
                updateRemoveButtons();
            });

            // Update remove button visibility
            function updateRemoveButtons() {
                const rows = $('.equipment-row');
                if (rows.length > 1) {
                    $('.remove-row').show();
                } else {
                    $('.remove-row').hide();
                }
            }

            updateRemoveButtons();
        });
    </script>
@endsection

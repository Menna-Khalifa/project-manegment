@extends('dashboard.layouts.master')

@section('title')
    Project Teams List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-teams.index') }}">Project Teams</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Project Teams List
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_project_team')
                            <a class="btn btn-primary" href="{{ route('project-teams.create') }}">
                                <i class="las la-user-plus"></i>
                                Add Team Member</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('project-teams.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="project_id">All Projects</label>
                                <select name="project_id" class="form-control">
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
                                <label for="user_id">All Users</label>
                                <select name="user_id" class="form-control select2">
                                    <option value="">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3 mt-2">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('project-teams.index') }}" class="btn btn-light">Clear</a>
                                {{-- bulk --}}
                                @can('add_project_team')
                                    <a class="btn btn-danger mt-3 mb-3 modal-effect" data-effect="effect-scale"
                                        data-toggle="modal" href="#bulkAssignModal">Bulk Action Modal</a>
                                @endcan
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project Name</th>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Added At</th>
                                    @if (auth()->user()->can('show_project_team') ||
                                            auth()->user()->can('edit_project_team') ||
                                            auth()->user()->can('delete_project_team'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectTeams as $team)
                                    <tr>
                                           <td>
                                            {{ ($projectTeams->currentPage() - 1) * $projectTeams->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            @can('show_project')
                                                <a href="{{ route('projects.show', $team->project_id) }}">
                                                    {{ $team->project->name ?? __('general.not_found') }}
                                                </a>
                                            @else
                                                {{ $team->project->name ?? __('general.not_found') }}
                                            @endcan
                                        </td>
                                        <td>
                                            @if ($team->user->type == 'admin')
                                                @can('show_admin')
                                                    <a href="{{ route('admin.show', $team->user_id) }}">
                                                        {{ $team->user->name ?? __('general.not_found') }}
                                                    </a>
                                                @else
                                                    {{ $team->user->name ?? __('general.not_found') }}
                                                @endcan
                                            @elseif ($team->user->type == 'user')
                                                @can('show_user')
                                                    <a href="{{ route('user.show', $team->user_id) }}">
                                                        {{ $team->user->name ?? __('general.not_found') }}
                                                    </a>
                                                @else
                                                    {{ $team->user->name ?? __('general.not_found') }}
                                                @endcan
                                            @elseif ($team->user->type == 'team')
                                                @can('show_team')
                                                    <a href="{{ route('team.show', $team->user_id) }}">
                                                        {{ $team->user->name ?? __('general.not_found') }}
                                                    </a>
                                                @else
                                                    {{ $team->user->name ?? __('general.not_found') }}
                                                @endcan
                                            @endif
                                        </td>
                                        <td>
                                            @if($team->is_lead)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-crown mr-1"></i>Leader
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Member</span>
                                            @endif
                                        </td>
                                        <td>{{ $team->created_at->format('Y-m-d H:i') }}</td>

                                        @if (auth()->user()->can('show_project_team') ||
                                                auth()->user()->can('edit_project_team') ||
                                                auth()->user()->can('delete_project_team'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_project_team')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project-teams.show', $team->id) }}">
                                                                <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                            </a>
                                                        @endcan
                                                        @can('edit_project_team')
                                                            <a class="dropdown-item"
                                                                href="{{ route('project-teams.edit', $team->id) }}">
                                                                <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('transfer_project_team')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#transferModal-{{ $team->id }}"
                                                                title="Transfer to Another Project">
                                                                <i
                                                                    class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;Transfer
                                                            </a>
                                                        @endcan
                                                        @can('delete_project_team')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $team->id }}"
                                                                title="Remove Team Member">
                                                                <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Remove
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    @can('transfer_project_team')
                                        <!-- Transfer Modal -->
                                        <div class="modal" id="transferModal-{{ $team->id }}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Transfer Team Member</h6>
                                                        <button aria-label="Close" class="close" data-dismiss="modal"
                                                            type="button">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('project-teams.transfer', $team->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Transfer <strong>{{ $team->user->name }}</strong> from
                                                                <strong>{{ $team->project->name }}</strong> to:
                                                            </p>

                                                            <div class="form-group">
                                                                <label for="new_project_id">Select New Project:</label>
                                                                <select name="new_project_id" id="new_project_id"
                                                                    class="form-control" required>
                                                                    <option value="">-- Select Project --</option>
                                                                    @foreach ($projects->where('id', '!=', $team->project_id) as $project)
                                                                        <option value="{{ $project->id }}">
                                                                            {{ $project->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="current_project_id"
                                                                value="{{ $team->project_id }}">

                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <strong>Warning:</strong> This action will move the user from
                                                                the current project to the selected project.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-warning">Transfer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $team->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Remove Team Member</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project-teams.destroy', $team->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to remove this team member?</p><br>
                                                        <input type="hidden" name="team_id"
                                                            value="{{ $team->id }}">
                                                        <input class="form-control" name="team_info"
                                                            value="{{ $team->user->name }} from {{ $team->project->name }}"
                                                            type="text" readonly>
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
                        {{ $projectTeams->appends(request()->query())->links('component.pagination', ['items' => $projectTeams]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Bulk Assignment Modal -->
@can('add_project_team')
    <div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Assign Users to Project</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('project-teams.bulk-assign') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bulk_project_id" class="form-label">Select Project</label>
                            <select class="form-control select2" style="width:100% !important;" id="bulk_project_id"
                                name="project_id" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="bulk_user_ids" class="form-label">Select Users</label>
                            <select class="form-control select2" style="width:100% !important;" id="bulk_user_ids"
                                name="user_ids[]" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} |
                                        {{ $user->phone }} |
                                        projectTeams {{ $user->activeProjectTeams->count() }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                        </div>

                        <!-- Project Lead Selection -->
                        <div class="form-group">
                            <label for="project_lead_id" class="form-label">Select Project Lead (Optional)</label>
                            <select class="form-control select2" style="width:100% !important;" id="project_lead_id"
                                name="project_lead_id">
                                <option value="">No Lead Selected</option>
                            </select>
                            <small class="text-muted">The lead must be selected from the users above</small>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Users</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection


@section('js')
<script>
    $(document).ready(function() {
        // Update lead options when users are selected
        $('#bulk_user_ids').on('change', function() {
            var selectedUsers = $(this).val();
            var leadSelect = $('#project_lead_id');
            
            // Clear previous options
            leadSelect.empty();
            leadSelect.append('<option value="">No Lead Selected</option>');
            
            // Add selected users as lead options
            if (selectedUsers && selectedUsers.length > 0) {
                $('#bulk_user_ids option:selected').each(function() {
                    var userId = $(this).val();
                    var userName = $(this).text();
                    leadSelect.append('<option value="' + userId + '">' + userName + '</option>');
                });
            }
            
            // Refresh select2
            leadSelect.trigger('change');
        });
    });
    </script>
@endsection
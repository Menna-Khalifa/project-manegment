@extends('dashboard.layouts.master')

@section('title')
    Edit Team Assignment
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-teams.index') }}">Project Teams</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Team Assignment
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Edit Team Assignment</h4>
                        <div>
                            <a class="btn btn-info" href="{{ route('project-teams.show', $projectTeam->id) }}">
                                <i class="las la-eye"></i> View Assignment
                            </a>
                            <a class="btn btn-secondary" href="{{ route('project-teams.index') }}">
                                <i class="las la-arrow-left"></i> Back to Project Teams
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project-teams.update', $projectTeam->id) }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Project <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_id') is-invalid @enderror" id="project_id"
                                        name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id', $projectTeam->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Team Member <span class="text-danger">*</span></label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id"
                                        name="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $projectTeam->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <!-- Hidden input to ensure we always get a value -->
                                        <input type="hidden" name="is_lead" value="0">

                                        <input type="checkbox" class="custom-control-input" id="is_lead" name="is_lead"
                                            value="1"
                                            {{ old('is_lead', $projectTeam->is_lead) == '1' || old('is_lead', $projectTeam->is_lead) === true ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="is_lead">
                                            <i class="fas fa-crown text-warning mr-1"></i>
                                            Set as Project Leader
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Only one leader per project is allowed. Setting this will remove the current leader.
                                    </small>
                                    @error('is_lead')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Current Status Info -->
                                <div class="form-group">
                                    <label class="form-label text-muted">Current Status:</label>
                                    <div
                                        class="alert {{ $projectTeam->is_lead ? 'alert-warning' : 'alert-info' }} py-2 mb-2">
                                        @if ($projectTeam->is_lead)
                                            <i class="fas fa-crown mr-1"></i>
                                            <strong>Current Project Leader</strong>
                                            <br><small>This user is currently leading this project</small>
                                        @else
                                            <i class="fas fa-user mr-1"></i>
                                            <strong>Project Member</strong>
                                            <br><small>Regular team member</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Current Leader Info (will be populated via JavaScript when project changes) -->
                                <div id="current-leader-info" class="form-group" style="display: none;">
                                    <label class="form-label text-muted">Current Project Leader:</label>
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="fas fa-user-crown mr-1"></i>
                                        <span id="current-leader-name">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment History (Optional) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label text-muted">Assignment Details:</label>
                                    <div class="card border-light">
                                        <div class="card-body py-2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <strong>Assigned Date:</strong>
                                                        {{ $projectTeam->created_at ? $projectTeam->created_at->format('M d, Y \a\t H:i') : 'N/A' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <strong>Last Updated:</strong>
                                                        {{ $projectTeam->updated_at ? $projectTeam->updated_at->format('M d, Y \a\t H:i') : 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Update Assignment
                            </button>
                            <a href="{{ route('project-teams.show', $projectTeam->id) }}" class="btn btn-info">
                                <i class="las la-eye"></i> View Assignment
                            </a>
                            <a href="{{ route('project-teams.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

<script>
$(document).ready(function() {
    // Store original project ID to track changes
    var originalProjectId = {{ $projectTeam->project_id }};
    var originalIsLead = {{ $projectTeam->is_lead ? 'true' : 'false' }};
    
    // Get current leader when project is selected (only if project changes)
    $('#project_id').on('change', function() {
        var projectId = $(this).val();
        var currentLeaderDiv = $('#current-leader-info');
        var currentLeaderName = $('#current-leader-name');
        
        // Only show current leader info if project changes
        if (projectId && projectId != originalProjectId) {
            // Make AJAX request to get current leader
            $.ajax({
                url: '{{ route("project-teams.get-leader") }}',
                method: 'GET',
                data: { project_id: projectId },
                success: function(response) {
                    if (response.leader && response.leader.id != {{ $projectTeam->user_id }}) {
                        currentLeaderName.text(response.leader.name);
                        currentLeaderDiv.show();
                    } else {
                        currentLeaderDiv.hide();
                    }
                },
                error: function() {
                    currentLeaderDiv.hide();
                }
            });
        } else {
            currentLeaderDiv.hide();
        }
    });

    // Show confirmation when changing leadership status
    $('#is_lead').on('change', function() {
        var isChecked = $(this).is(':checked');
        var projectId = $('#project_id').val();
        
        // If unchecking leadership
        if (!isChecked && originalIsLead) {
            if (!confirm('Are you sure you want to remove this user as project leader?')) {
                $(this).prop('checked', true);
                return;
            }
        }
        
        // If checking leadership and project changed
        if (isChecked && projectId && projectId != originalProjectId) {
            var currentLeaderName = $('#current-leader-name').text();
            if (currentLeaderName && currentLeaderName !== '-') {
                if (!confirm('This will replace ' + currentLeaderName + ' as the project leader. Are you sure?')) {
                    $(this).prop('checked', false);
                }
            }
        }
        
        // If setting as leader on same project but wasn't leader before
        if (isChecked && !originalIsLead && projectId == originalProjectId) {
            // Check if there's another leader in this project
            $.ajax({
                url: '{{ route("project-teams.get-leader") }}',
                method: 'GET',
                data: { project_id: projectId },
                success: function(response) {
                    if (response.leader && response.leader.id != {{ $projectTeam->user_id }}) {
                        if (!confirm('This will replace ' + response.leader.name + ' as the project leader. Are you sure?')) {
                            $('#is_lead').prop('checked', false);
                        }
                    }
                }
            });
        }
    });

    // Warn about user change if currently leader
    $('#user_id').on('change', function() {
        var newUserId = $(this).val();
        if (originalIsLead && newUserId != {{ $projectTeam->user_id }}) {
            alert('Warning: You are changing the user for a project leader. Make sure this is intended.');
        }
    });
});
</script>
@endsection

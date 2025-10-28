@extends('dashboard.layouts.master')

@section('title')
    Add Team Member
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-teams.index') }}">Project Teams</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add Team Member
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
                        <h4 class="card-title mg-b-0">Add New Team Member</h4>
                        <a class="btn btn-secondary" href="{{ route('project-teams.index') }}">
                            <i class="las la-arrow-left"></i> Back to Project Teams
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project-teams.store') }}" method="post">
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
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                        <input type="checkbox" class="custom-control-input" id="is_lead" name="is_lead"
                                            value="1" {{ old('is_lead') ? 'checked' : '' }}>
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
                                <!-- Current Leader Info (will be populated via JavaScript) -->
                                <div id="current-leader-info" class="form-group" style="display: none;">
                                    <label class="form-label text-muted">Current Project Leader:</label>
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="fas fa-user-crown mr-1"></i>
                                        <span id="current-leader-name">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-user-plus"></i> Add Team Member
                            </button>
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
    // Get current leader when project is selected
    $('#project_id').on('change', function() {
        var projectId = $(this).val();
        var currentLeaderDiv = $('#current-leader-info');
        var currentLeaderName = $('#current-leader-name');
        
        if (projectId) {
            // Make AJAX request to get current leader
            $.ajax({
                url: '{{ route("project-teams.get-leader") }}',
                method: 'GET',
                data: { project_id: projectId },
                success: function(response) {
                    if (response.leader) {
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

    // Show warning when leader checkbox is checked
    $('#is_lead').on('change', function() {
        var isChecked = $(this).is(':checked');
        var projectId = $('#project_id').val();
        
        if (isChecked && projectId) {
            var currentLeaderName = $('#current-leader-name').text();
            if (currentLeaderName && currentLeaderName !== '-') {
                if (!confirm('This will replace ' + currentLeaderName + ' as the project leader. Are you sure?')) {
                    $(this).prop('checked', false);
                }
            }
        }
    });
});
</script>
@endsection
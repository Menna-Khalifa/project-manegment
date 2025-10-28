@extends('dashboard.layouts.master')

@section('title')
    User Details - {{ $admin->name }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('admin.index') }}">{{ __('layouts/main-sidebar.users_list') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ $admin->name }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- User Profile Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-gradient profile-header-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3 text-center">
                            <div class="profile-avatar">
                                @if ($admin->getFirstMediaUrl('avatars') != null)
                                    <img src="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}" alt="{{ $admin->name }}"
                                        class="profile-image">
                                @else
                                    <img src="{{ URL::asset('dashboard/assets/img/faces/default_user.png') }}"
                                        alt="Default Avatar" class="profile-image">
                                @endif
                                <button class="profile-edit-btn" data-toggle="modal" data-target="#editModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="text-white mb-2">{{ $admin->name ?? __('general.not_found') }}</h3>
                            <p class="text-white-50 mb-3">{{ $admin->email ?? __('general.not_found') }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-light badge-lg">
                                    <i class="fas fa-user-tag me-2"></i>
                                    {{ $admin->getRoleNames()->first() ?? __('general.not_found') }}
                                </span>
                                <span class="badge badge-light badge-lg">
                                    <i class="fas fa-users me-2"></i>
                                    {{ $admin->group->name ?? __('general.not_found') }}
                                </span>
                                <span class="badge badge-{{ $admin->status == 'active' ? 'success' : 'danger' }} badge-lg">
                                    <i
                                        class="fas fa-{{ $admin->status == 'active' ? 'check-circle' : 'times-circle' }} me-2"></i>
                                    {{ $admin->status == 'active' ? __('admins.enabled') : __('admins.not_enabled') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 text-lg-end">
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <h4 class="text-white mb-0">
                                        {{ $admin->projectTeams ? $admin->projectTeams->where('project.status', 'active')->count() : 0 }}
                                    </h4>
                                    <small class="text-white-50">Active Projects</small>
                                </div>
                                <div class="stat-item mt-2">
                                    <h4 class="text-white mb-0">
                                        {{ $admin->projectTeams ? $admin->projectTeams->count() : 0 }}</h4>
                                    <small class="text-white-50">Total Projects</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details and Projects -->
    <div class="row">
        <div class="col-lg-4">
            <!-- Personal Information -->
            <div class="card modern-card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <label>Email</label>
                                <span>{{ $admin->email ?? __('general.not_found') }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <label>Phone</label>
                                <span>{{ $admin->phone ?? __('general.not_found') }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="info-content">
                                <label>Joined Date</label>
                                <span>{{ $admin->created_at ? $admin->created_at->format('M d, Y') : __('general.not_found') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Statistics -->
            <div class="card modern-card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card bg-success-light">
                            <div class="stat-icon">
                                <i class="fas fa-project-diagram text-success"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ $admin->projectTeams->where('project.status', 'active')->count() }}</h4>
                                <span>Active Projects</span>
                            </div>
                        </div>

                        <div class="stat-card bg-info-light">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle text-info"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ $admin->projectTeams->where('project.status', 'delivered')->count() }}</h4>
                                <span>Completed</span>
                            </div>
                        </div>

                        <div class="stat-card bg-warning-light">
                            <div class="stat-icon">
                                <i class="fas fa-tasks text-warning"></i>
                            </div>
                            <div class="stat-info">
                                @php
                                    $activeProjects = $admin->projectTeams->where('project.status', 'active');
                                    $totalProgress = 0;
                                    $count = 0;
                                    foreach ($activeProjects as $projectTeam) {
                                        if ($projectTeam->project) {
                                            $totalQty = $projectTeam->project->projectItems->sum('qty');
                                            $executedQty = $projectTeam->project->projectItems->sum('executed_qty');
                                            $progress = $totalQty > 0 ? ($executedQty / $totalQty) * 100 : 0;
                                            $totalProgress += $progress;
                                            $count++;
                                        }
                                    }
                                    $avgProgress = $count > 0 ? round($totalProgress / $count) : 0;
                                @endphp
                                <h4>{{ $avgProgress }}%</h4>
                                <span>Avg Progress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Active Projects -->
            <div class="card modern-card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Active Projects</h5>
                    <span
                        class="badge badge-primary">{{ $admin->projectTeams->where('project.status', '!=', 'completed')->count() }}
                        Projects</span>
                </div>
                <div class="card-body p-0">
                    @forelse($admin->projectTeams->where('project.status', '!=','completed') as $projectTeam)
                        @php $project = $projectTeam->project; @endphp
                        <div class="project-card">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="project-info">
                                        <h6 class="project-title">
                                            <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                                        </h6>
                                        <p class="project-client">
                                            <i class="fas fa-user me-2"></i>{{ $project->type }}
                                        </p>
                                        <div class="project-meta">
                                            <span class="badge badge-outline-primary me-2">
                                                <i class="fas fa-hashtag me-1"></i>{{ $project->po_num }}
                                            </span>
                                            <span class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $project->start_date->format('M d') }} -
                                                {{ $project->end_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="project-progress">
                                        @php
                                            $totalQty = $project->projectItems->sum('qty');
                                            $executedQty = $project->projectItems->sum('executed_qty');
                                            $progress = $totalQty > 0 ? round(($executedQty / $totalQty) * 100) : 0;
                                        @endphp
                                        <div class="progress-info mb-2">
                                            <span class="progress-label">Progress</span>
                                            <span class="progress-value">{{ $progress }}%</span>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar {{ $progress >= 75 ? 'bg-success' : ($progress >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-end">
                                    <div class="project-cost mb-2">
                                        <strong class="text-success">{{ number_format($project->project_cost) }}
                                            SAR</strong>
                                    </div>
                                    <div class="project-actions">
                                        <a href="{{ route('projects.show', $project->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        @can('transfer_project_team')
                                            <a class="btn btn-outline-warning btn-sm modal-effect" data-effect="effect-scale"
                                                data-toggle="modal" href="#transferModal-{{ $projectTeam->id }}"
                                                title="Transfer to Another Project">
                                                <i class="fas fa-exchange-alt me-1"></i>Transfer
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                            <!-- Project Details Summary -->
                            <div class="project-summary mt-3">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="summary-item">
                                            <i class="fas fa-list-ul text-primary"></i>
                                            <span class="d-block">{{ $project->projectItems->count() }}</span>
                                            <small class="text-muted">Items</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="summary-item">
                                            <i class="fas fa-users text-success"></i>
                                            <span class="d-block">{{ $project->projectTeams->count() }}</span>
                                            <small class="text-muted">Team</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="summary-item">
                                            <i class="fas fa-tools text-warning"></i>
                                            <span class="d-block">{{ $project->projectEquipment->count() }}</span>
                                            <small class="text-muted">Equipment</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="summary-item">
                                            <i class="fas fa-clock text-info"></i>
                                            <span
                                                class="d-block">{{ $project->start_date->diffInDays($project->end_date) }}</span>
                                            <small class="text-muted">Days</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @can('transfer_project_team')
                            <!-- Transfer Modal - Unique for each ProjectTeam -->
                            <div class="modal fade" id="transferModal-{{ $projectTeam->id }}" tabindex="-1"
                                role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header bg-warning text-white">
                                            <h6 class="modal-title">
                                                <i class="fas fa-exchange-alt me-2"></i>
                                                Transfer Team Member
                                            </h6>
                                            <button aria-label="Close" class="close text-white" data-dismiss="modal"
                                                type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('project-teams.transfer', $projectTeam->id) }}"
                                            method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="transfer-info-card mb-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-3 text-center">
                                                            @if ($admin->getFirstMediaUrl('avatars'))
                                                                <img src="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}"
                                                                    alt="{{ $admin->name }}" class="transfer-avatar">
                                                            @else
                                                                <div class="transfer-avatar-placeholder">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-9">
                                                            <h6 class="mb-1">{{ $admin->name }}</h6>
                                                            <small class="text-muted">{{ $admin->email }}</small>
                                                            <div class="mt-2">
                                                                <span
                                                                    class="badge badge-info">{{ $admin->getRoleNames()->first() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="transfer-flow mb-3">
                                                    <div class="row align-items-center text-center">
                                                        <div class="col-5">
                                                            <div class="project-box from-project">
                                                                <i class="fas fa-project-diagram mb-2"></i>
                                                                <h6>{{ $project->name }}</h6>
                                                                <small class="text-muted">Current Project</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <i class="fas fa-arrow-right text-warning fa-2x"></i>
                                                        </div>
                                                        <div class="col-5">
                                                            <div class="project-box to-project">
                                                                <i class="fas fa-project-diagram mb-2"></i>
                                                                <h6>New Project</h6>
                                                                <small class="text-muted">Select Below</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="new_project_id_{{ $projectTeam->id }}" class="form-label">
                                                        <i class="fas fa-clipboard-list me-2"></i>Select New Project
                                                    </label>
                                                    <select name="new_project_id" id="new_project_id_{{ $projectTeam->id }}"
                                                        class="form-control select2" style="width: 100% !important;" required>
                                                        <option value="">-- Choose Project --</option>
                                                        @foreach ($projects->where('id', '!=', $project->id)->where('status', 'active') as $availableProject)
                                                            <option value="{{ $availableProject->id }}"
                                                                data-type="{{ $availableProject->type }}"
                                                                data-team-count="{{ $availableProject->projectTeams->count() }}">
                                                                {{ $availableProject->name }} - {{ $availableProject->po_num }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <input type="hidden" name="current_project_id" value="{{ $project->id }}">
                                                <input type="hidden" name="user_id" value="{{ $admin->id }}">

                                                <div class="alert alert-warning">
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                                        <div>
                                                            <strong>Transfer Warning:</strong>
                                                            <ul class="mb-0 mt-2">
                                                                <li>The team member will be removed from the current project
                                                                </li>
                                                                <li>All permissions and access will be transferred</li>
                                                                <li>This action cannot be easily undone</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times mr-1"></i>Cancel
                                                </button>
                                                <button type="submit" class="btn btn-warning" id="confirmTransfer">
                                                    <i class="fas fa-exchange-alt mr-1"></i>Confirm Transfer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endcan

                    @empty
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-briefcase text-muted fa-3x mb-3"></i>
                                <h5 class="text-muted">No Active Projects</h5>
                                <p class="text-muted">This user is not assigned to any active projects.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- Completed Projects (if any) -->
            @if ($admin->projectTeams->where('project.status', 'completed')->count() > 0)
                <div class="card modern-card mt-4">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Completed Projects</h5>
                        <span
                            class="badge badge-success">{{ $admin->projectTeams->where('project.status', 'completed')->count() }}
                            Projects</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover modern-table">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Cost</th>
                                        <th>Completed Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admin->projectTeams->where('project.status', 'completed') as $projectTeam)
                                        @php $project = $projectTeam->project; @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $project->name }}</strong>
                                                <br><small class="text-muted">{{ $project->po_num }}</small>
                                            </td>
                                            <td>{{ $project->type }}</td>
                                            <td>{{ $project->start_date->diffInDays($project->end_date) }} days</td>
                                            <td class="text-success font-weight-bold">
                                                {{ number_format($project->project_cost) }} SAR</td>
                                            <td>{{ $project->end_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('projects.show', $project->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Profile Photo Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('admins.edit_image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update_profile_photo', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image">{{ __('admins.image') }}</label>
                            @if ($admin->getFirstMediaUrl('avatars') != null)
                                <input type="file" class="dropify" name="photo" data-height="100"
                                    data-default-file="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}" />
                            @else
                                <input type="file" class="dropify" name="photo" data-height="100" />
                            @endif
                        </div>
                        <button class="btn btn-primary waves-effect waves-light w-md"
                            type="submit">{{ __('admins.sure') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .profile-header-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            margin-bottom: 2rem;
        }

        .profile-avatar {
            position: relative;
            display: inline-block;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .profile-edit-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #007bff;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-edit-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        .profile-stats .stat-item {
            text-align: center;
        }

        .modern-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .info-list {
            padding: 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #007bff;
        }

        .info-content {
            flex: 1;
        }

        .info-content label {
            display: block;
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .info-content span {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }

        .stat-info h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .stat-info span {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .bg-success-light {
            background: rgba(40, 167, 69, 0.1);
        }

        .bg-info-light {
            background: rgba(23, 162, 184, 0.1);
        }

        .bg-warning-light {
            background: rgba(255, 193, 7, 0.1);
        }

        .project-card {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            background: #f8f9fa;
        }

        .project-card:last-child {
            border-bottom: none;
        }

        .project-title {
            margin-bottom: 0.5rem;
        }

        .project-title a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .project-title a:hover {
            text-decoration: underline;
        }

        .project-client {
            color: #6c757d;
            margin-bottom: 0.75rem;
        }

        .project-meta .badge {
            font-size: 0.75rem;
        }

        .badge-outline-primary {
            color: #007bff;
            border: 1px solid #007bff;
            background-color: transparent;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .progress-label {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .progress-value {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .progress-sm {
            height: 6px;
        }

        .project-summary {
            border-top: 1px solid #f0f0f0;
            padding-top: 1rem;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item i {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .summary-item span {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .empty-state {
            padding: 2rem;
        }

        .modern-table thead th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }

        .modern-table tbody tr {
            border: none;
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .profile-image {
                width: 80px;
                height: 80px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .project-card .row {
                text-align: center;
            }

            .project-card .col-lg-3 {
                margin-top: 1rem;
            }
        }
    </style>

    <style>
        /* Modal Enhancements */
        .modal-header.bg-warning {
            border-bottom: none;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .transfer-info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #e9ecef;
        }

        .transfer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }

        .transfer-avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .transfer-flow {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .project-box {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .from-project {
            border-color: #007bff;
            background: linear-gradient(135deg, #e7f1ff 0%, #ffffff 100%);
        }

        .to-project {
            border-color: #ffc107;
            background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
        }

        .project-box i {
            font-size: 1.5rem;
            color: #6c757d;
        }

        .from-project i {
            color: #007bff;
        }

        .to-project i {
            color: #ffc107;
        }

        .select-project {
            border-radius: 8px;
            border: 2px solid #dee2e6;
            padding: 0.75rem;
            font-size: 0.95rem;
        }

        .select-project:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .alert-warning {
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }

        .modal-footer .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
        }

        #confirmTransfer:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('js')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced transfer modal functionality
    document.querySelectorAll('.select-project').forEach(function(select) {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const modal = this.closest('.modal');
            const confirmButton = modal.querySelector('#confirmTransfer');

            if (this.value) {
                confirmButton.disabled = false;
                confirmButton.innerHTML = '<i class="fas fa-exchange-alt me-1"></i>Transfer to ' + selectedOption.text.split('(')[0].trim();

                // Update the to-project box
                const toProjectBox = modal.querySelector('.to-project h6');
                if (toProjectBox) {
                    toProjectBox.textContent = selectedOption.text.split('(')[0].trim();
                }
            } else {
                confirmButton.disabled = true;
                confirmButton.innerHTML = '<i class="fas fa-exchange-alt me-1"></i>Confirm Transfer';

                const toProjectBox = modal.querySelector('.to-project h6');
                if (toProjectBox) {
                    toProjectBox.textContent = 'New Project';
                }
            }
        });
    });

    // Add confirmation on form submit
    document.querySelectorAll('form[action*="transfer"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const selectedProject = this.querySelector('.select-project').selectedOptions[0];
            if (selectedProject) {
                const projectName = selectedProject.text.split('(')[0].trim();
                if (!confirm(`Are you sure you want to transfer this team member to "${projectName}"?`)) {
                    e.preventDefault();
                }
            }
        });
    });
});
</script>

    <script>
        $(document).ready(function() {
            // Animate progress bars
            $('.progress-bar').each(function(index) {
                var $bar = $(this);
                var width = $bar.css('width');
                if (width && width !== '0px') {
                    var percentage = (parseFloat(width) / $bar.parent().width()) * 100;
                    $bar.css('width', '0%');
                    setTimeout(function() {
                        $bar.animate({
                            'width': percentage + '%'
                        }, 1000);
                    }, 200 + (index * 100));
                }
            });

            // Animate stat cards
            $('.stat-card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                }).delay(index * 150).animate({
                    opacity: 1
                }, 600);
            });

            // Animate project cards
            $('.project-card').each(function(index) {
                $(this).css('opacity', '0').delay(index * 100).animate({
                    opacity: 1
                }, 500);
            });

            // Counter animation for stats
            $('.stat-info h4').each(function() {
                var $this = $(this);
                var countTo = parseInt($this.text());

                if (!isNaN(countTo)) {
                    $this.text('0');
                    $({
                        countNum: 0
                    }).animate({
                        countNum: countTo
                    }, {
                        duration: 1500,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function() {
                            $this.text(countTo);
                        }
                    });
                }
            });

            // Initialize tooltips
            $('[title]').tooltip();
        });
    </script>
@endsection

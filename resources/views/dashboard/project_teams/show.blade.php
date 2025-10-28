@extends('dashboard.layouts.master')

@section('title')
    Team Member Details
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-teams.index') }}">Project Teams</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Team Member Details
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
                        <h4 class="card-title mg-b-0">Team Member Details</h4>
                        <div>
                            @can('edit_project_team')
                                <a class="btn btn-primary" href="{{ route('project-teams.edit', $projectTeam->id) }}">
                                    <i class="las la-edit"></i> Edit Assignment
                                </a>
                            @endcan
                            <a class="btn btn-secondary" href="{{ route('project-teams.index') }}">
                                <i class="las la-arrow-left"></i> Back to Project Teams
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Team Member Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Project Name:</strong></td>
                                    <td>{{ $projectTeam->project->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project PO Number:</strong></td>
                                    <td>{{ $projectTeam->project->po_num ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $projectTeam->project->status == 'active' ? 'success' : 'info' }}">
                                            {{ ucfirst($projectTeam->project->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Team Member:</strong></td>
                                    <td>
                                        {{ $projectTeam->user->name ?? 'N/A' }}
                                        @if ($projectTeam->user && $projectTeam->user->is_leader)
                                            <span class="badge badge-primary">Leader</span>
                                            @else
                                            <span class="badge badge-primary">Member</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Added At:</strong></td>
                                    <td>{{ $projectTeam->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $projectTeam->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Project Information -->
                    @if($projectTeam->project)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Project Information</h5>
                                <div class="border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Type:</strong> {{ $projectTeam->project->type ?? 'N/A' }}</p>
                                            <p><strong>Start Date:</strong> {{ $projectTeam->project->start_date ? $projectTeam->project->start_date->format('Y-m-d') : 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>End Date:</strong> {{ $projectTeam->project->end_date ? $projectTeam->project->end_date->format('Y-m-d') : 'N/A' }}</p>
                                            <p><strong>Project Cost:</strong> {{ $projectTeam->project->project_cost ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Description:</strong></p>
                                            <p>{{ $projectTeam->project->description ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Other Team Members -->
                    @if($projectTeam->project && $projectTeam->project->projectTeams && $projectTeam->project->projectTeams->count() > 1)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Other Team Members</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Added At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projectTeam->project->projectTeams as $member)
                                                @if($member->id != $projectTeam->id)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $member->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $member->created_at->format('Y-m-d H:i') }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

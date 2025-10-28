@extends('dashboard.layouts.master')

@section('title')
    Project Item Details
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-items.index') }}">Project Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Project Item Details
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
                        <h4 class="card-title mg-b-0">Project Item Details</h4>
                        <div>
                            @can('edit_project_item')
                                <a class="btn btn-primary" href="{{ route('project-items.edit', $projectItem->id) }}">
                                    <i class="las la-edit"></i> Edit Project Item
                                </a>
                            @endcan
                            <a class="btn btn-secondary" href="{{ route('project-items.index') }}">
                                <i class="las la-arrow-left"></i> Back to Project Items
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Project Item Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Project:</strong></td>
                                    <td>{{ $projectItem->project->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Section:</strong></td>
                                    <td>{{ $projectItem->section->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Section Item:</strong></td>
                                    <td>{{ $projectItem->sectionItem->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity:</strong></td>
                                    <td>{{ $projectItem->qty }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Received Quantity:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $projectItem->received_qty == $projectItem->qty ? 'success' : 'warning' }}">
                                            {{ $projectItem->received_qty }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Executed Quantity:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $projectItem->executed_qty == $projectItem->received_qty ? 'success' : 'info' }}">
                                            {{ $projectItem->executed_qty }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Expected Arrival:</strong></td>
                                    <td>{{ $projectItem->expected_arrival ? $projectItem->expected_arrival->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $projectItem->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Progress Information -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Progress Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h6>Remaining Quantity</h6>
                                            <h4>{{ $projectItem->remaining_qty }}</h4>
                                            <small>{{ $projectItem->qty - $projectItem->received_qty }} items pending delivery</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h6>Pending Execution</h6>
                                            <h4>{{ $projectItem->pending_execution_qty }}</h4>
                                            <small>{{ $projectItem->received_qty - $projectItem->executed_qty }} items pending execution</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h6>Completion Rate</h6>
                                            <h4>{{ $projectItem->qty > 0 ? round(($projectItem->executed_qty / $projectItem->qty) * 100, 2) : 0 }}%</h4>
                                            <small>Based on executed quantity</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Update Forms -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            @can('edit_project_item')
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Update Received Quantity</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('project-items.update-received-qty', $projectItem->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Received Quantity (Max: {{ $projectItem->qty }})</label>
                                                <input type="number" name="received_qty" class="form-control" 
                                                       value="{{ $projectItem->received_qty }}" 
                                                       min="0" max="{{ $projectItem->qty }}" required>
                                            </div>
                                            @can('edit_received_project_item')
                                            <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                            @endcan
                                        </form>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <div class="col-md-6">
                            @can('edit_project_item')
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Update Executed Quantity</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('project-items.update-executed-qty', $projectItem->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Executed Quantity (Max: {{ $projectItem->received_qty }})</label>
                                                <input type="number" name="executed_qty" class="form-control" 
                                                       value="{{ $projectItem->executed_qty }}" 
                                                       min="0" max="{{ $projectItem->received_qty }}" required>
                                            </div>
                                            @can('edit_executed_project_item')
                                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                                            @endcan
                                        </form>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

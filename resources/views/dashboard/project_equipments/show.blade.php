@extends('dashboard.layouts.master')

@section('title')
    Project Equipment Details
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-equipments.index') }}">Project Equipment</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Equipment Details
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
                        <h4 class="card-title mg-b-0">Project Equipment Details</h4>
                        <div>
                            @can('edit_project_equipment')
                                <a class="btn btn-primary" href="{{ route('project-equipments.edit', $projectEquipment->id) }}">
                                    <i class="las la-edit"></i> Edit Assignment
                                </a>
                            @endcan
                            <a class="btn btn-secondary" href="{{ route('project-equipments.index') }}">
                                <i class="las la-arrow-left"></i> Back to Project Equipment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Equipment Assignment Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Project Name:</strong></td>
                                    <td>{{ $projectEquipment->project->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project PO Number:</strong></td>
                                    <td>{{ $projectEquipment->project->po_num ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Equipment Name:</strong></td>
                                    <td>{{ $projectEquipment->equipment->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity:</strong></td>
                                    <td>{{ $projectEquipment->qty }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $projectEquipment->status == 'available' ? 'success' : ($projectEquipment->status == 'delivered' ? 'info' : ($projectEquipment->status == 'unavailable' ? 'warning' : 'danger')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $projectEquipment->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Added At:</strong></td>
                                    <td>{{ $projectEquipment->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $projectEquipment->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Project Information -->
                    @if($projectEquipment->project)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Project Information</h5>
                                <div class="border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Type:</strong> {{ $projectEquipment->project->type ?? 'N/A' }}</p>
                                            <p><strong>Start Date:</strong> {{ $projectEquipment->project->start_date ? $projectEquipment->project->start_date->format('Y-m-d') : 'N/A' }}</p>
                                            <p><strong>Project Status:</strong>
                                                <span class="badge badge-{{ $projectEquipment->project->status == 'active' ? 'success' : 'info' }}">
                                                    {{ ucfirst($projectEquipment->project->status ?? 'N/A') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>End Date:</strong> {{ $projectEquipment->project->end_date ? $projectEquipment->project->end_date->format('Y-m-d') : 'N/A' }}</p>
                                            <p><strong>Project Cost:</strong> {{ $projectEquipment->project->project_cost ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Description:</strong></p>
                                            <p>{{ $projectEquipment->project->description ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Equipment Information -->
                    @if($projectEquipment->equipment)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Equipment Information</h5>
                                <div class="border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Name:</strong> {{ $projectEquipment->equipment->name ?? 'N/A' }}</p>
                                            <p><strong>Type:</strong> {{ $projectEquipment->equipment->type ?? 'N/A' }}</p>
                                            <p><strong>Status:</strong>
                                                <span class="badge badge-{{ $projectEquipment->equipment->status == 'available' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($projectEquipment->equipment->status ?? 'N/A') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Description:</strong></p>
                                            <p>{{ $projectEquipment->equipment->description ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

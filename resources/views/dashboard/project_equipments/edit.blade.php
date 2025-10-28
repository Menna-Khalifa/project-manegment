@extends('dashboard.layouts.master')

@section('title')
    Edit Project Equipment
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-equipments.index') }}">Project Equipment</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Project Equipment
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
                        <h4 class="card-title mg-b-0">Edit New Project Equipment</h4>
                        <a class="btn btn-secondary" href="{{ route('project-equipments.index') }}">
                            <i class="las la-arrow-left"></i> Back to Project Equipment
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project-equipments.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Project <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $projectEquipment->project_id) == $project->id ? 'selected' : '' }}>
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
                                    <label for="equipment_id">Equipment <span class="text-danger">*</span></label>
                                    <select class="form-control @error('equipment_id') is-invalid @enderror" id="equipment_id" name="equipment_id" required>
                                        <option value="">Select Equipment</option>
                                        @foreach($equipment as $item)
                                            <option value="{{ $item->id }}" {{ old('equipment_id', $projectEquipment->equipment_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('equipment_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('qty') is-invalid @enderror" 
                                           id="qty" name="qty" value="{{ old('qty', $projectEquipment->qty) }}" min="1" required>
                                    @error('qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" {{ old('status', $projectEquipment->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="unavailable" {{ old('status', $projectEquipment->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="delivered" {{ old('status', $projectEquipment->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="not_delivered" {{ old('status', $projectEquipment->status) == 'not_delivered' ? 'selected' : '' }}>Not Delivered</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Edit Equipment
                            </button>
                            <a href="{{ route('project-equipments.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('dashboard.layouts.master')

@section('title')
    Edit Model
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_models.index') }}">Project Models</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Model
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Edit: {{ $model->name }}</h4>
                        <div>
                            <a class="btn btn-secondary" href="{{ route('project_models.index') }}">
                                <i class="las la-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project_models.update', $model->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $model->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_type_id">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_type_id') is-invalid @enderror" id="project_type_id" name="project_type_id" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('project_type_id', $model->project_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $model->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Update
                            </button>
                            <a href="{{ route('project_models.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
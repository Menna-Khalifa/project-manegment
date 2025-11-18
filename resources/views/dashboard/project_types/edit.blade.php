@extends('dashboard.layouts.master')

@section('title')
    Edit Type
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_types.index') }}">Project Types</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Type
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Edit: {{ $type->name }}</h4>
                        <div>
                            <a class="btn btn-secondary" href="{{ route('project_types.index') }}">
                                <i class="las la-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project_types.update', $type->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $type->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="type" value="{{$type->type}}">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $type->description) }}</textarea>
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
                            <a href="{{ route('project_types.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
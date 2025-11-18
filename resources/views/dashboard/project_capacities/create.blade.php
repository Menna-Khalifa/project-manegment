@extends('dashboard.layouts.master')

@section('title')
    Add Capacity
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_capacities.index') }}">Capacities</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add Capacity
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Add New Capacity</h4>
                        <a class="btn btn-secondary" href="{{ route('project_capacities.index') }}">
                            <i class="las la-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project_capacities.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Save
                            </button>
                            <a href="{{ route('project_capacities.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
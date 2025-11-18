@extends('dashboard.layouts.master')

@section('title')
    Edit Volt
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_volts.index') }}">Volts</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Volt
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content_between">
                        <h4 class="card-title mg-b-0">Edit: {{ $volt->value }}</h4>
                        <div>
                            <a class="btn btn-secondary" href="{{ route('project_volts.index') }}">
                                <i class="las la-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project_volts.update', $volt->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="value">Value <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $volt->value) }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Update
                            </button>
                            <a href="{{ route('project_volts.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
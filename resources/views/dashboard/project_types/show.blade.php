@extends('dashboard.layouts.master')

@section('title')
    Type Details - {{ $type->name }}
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_types.index') }}">Project Types</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ $type->name }}
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Type Information</h5>
                    <div class="btn-group">
                        <a href="{{ route('project_types.edit', $type->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('project_types.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Name</label>
                                <div class="info-value">{{ $type->name }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Type</label>
                                <div class="info-value">{{ ucfirst($type->type) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Description</label>
                                <div class="info-value">{{ $type->description ?? '-' }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Models Count</label>
                                <div class="info-value">{{ $type->projectModels->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
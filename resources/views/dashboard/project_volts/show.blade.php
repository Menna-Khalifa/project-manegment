@extends('dashboard.layouts.master')

@section('title')
    Volt Details - {{ $volt->value }}
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_volts.index') }}">Volts</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ $volt->value }}
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Volt Information</h5>
                    <div class="btn-group">
                        <a href="{{ route('project_volts.edit', $volt->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('project_volts.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Value</label>
                                <div class="info-value">{{ $volt->value }}</div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
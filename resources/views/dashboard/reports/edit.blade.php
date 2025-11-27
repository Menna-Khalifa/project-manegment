<!-- resources/views/dashboard/reports/edit.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Edit Report
@endsection

@section('css')
    <style>
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            position: relative;
            width: 150px;
            height: 150px;
            border: 2px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .existing-image {
            position: relative;
            width: 150px;
            height: 150px;
            border: 2px solid #28a745;
            border-radius: 5px;
            overflow: hidden;
        }

        .existing-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .existing-image .delete-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .custom-fields-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Report
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title mg-b-0">Edit Report</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Report Type <span class="text-danger">*</span></label>
                                    <select name="report_type" id="report_type" class="form-control" required>
                                        <option value="">Select Report Type</option>
                                        <option value="start_up_report"
                                            {{ $report->report_type == 'start_up_report' ? 'selected' : '' }}>
                                            Packaged Unit Start Up Report
                                        </option>
                                        <option value="work_completed"
                                            {{ $report->report_type == 'work_completed' ? 'selected' : '' }}>
                                            Work Completed Report
                                        </option>
                                        <option value="sites_refer_report"
                                            {{ $report->report_type == 'sites_refer_report' ? 'selected' : '' }}>
                                            Sites Refer Report
                                        </option>
                                    </select>
                                    @error('report_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select name="project_amer_id" id="project_amer_id" class="form-control select2">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_amer_id', $report->project_amer_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->po_num }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_amer_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Report Date <span class="text-danger">*</span></label>
                                    <input type="date" name="report_date" class="form-control"
                                        value="{{ old('report_date', $report->report_date->format('Y-m-d')) }}" required>
                                    @error('report_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store <span class="text-danger">*</span></label>
                                    <select name="store_id" id="store_id" class="form-control select2" required>
                                        <option value="">Select Store</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" data-city="{{ $store->city }}"
                                                {{ old('store_id', $report->store_id) == $store->id ? 'selected' : '' }}>
                                                {{ $store->uuid }} - {{ $store->name }} - {{ $store->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Store Manual Input for Sites Refer Report -->
                        <div id="store-manual-fields" style="display: {{ $report->report_type == 'sites_refer_report' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Store Name <span class="text-danger">*</span></label>
                                        <input type="text" name="store_name" class="form-control"
                                            placeholder="Enter store name"
                                            value="{{ old('store_name', $report->store_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <input type="text" name="store_city" class="form-control"
                                            placeholder="Enter city"
                                            value="{{ old('store_city', $report->store_city ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Units Section for Sites Refer Report -->
                        <div id="sites-refer-section" style="display: {{ $report->report_type == 'sites_refer_report' ? 'block' : 'none' }};">
                            <div class="card mt-4">
                                <div class="card-header" style="background-color: #003366; color: white;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-cubes"></i> Units Information</h5>
                                        <button type="button" id="add-unit-btn" class="btn btn-sm btn-light">
                                            <i class="fas fa-plus"></i> Add Unit
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="units-container">
                                        @if($report->report_type == 'sites_refer_report' && isset($report->units))
                                            @foreach($report->units as $index => $unit)
                                                <div class="card unit-card mb-3" data-unit-index="{{ $index }}">
                                                    <div class="card-header" style="background-color: #f8f9fa;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0"><i class="fas fa-cube"></i> Unit #{{ $index + 1 }}</h6>
                                                            <button type="button" class="btn btn-sm btn-danger remove-unit-btn">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <!-- Brand -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Brand <span class="text-danger">*</span></label>
                                                                    <select name="units[{{ $index }}][brand_id]" class="form-control" required>
                                                                        <option value="">Select Brand</option>
                                                                        @foreach ($brands as $brand)
                                                                            <option value="{{ $brand->id }}" {{ ($unit['brand_id'] ?? '') == $brand->id ? 'selected' : '' }}>
                                                                                {{ $brand->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Type -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Type <span class="text-danger">*</span></label>
                                                                    <select name="units[{{ $index }}][type_id]" class="form-control" required>
                                                                        <option value="">Select Type</option>
                                                                        @foreach ($types as $type)
                                                                            <option value="{{ $type->id }}" {{ ($unit['type_id'] ?? '') == $type->id ? 'selected' : '' }}>
                                                                                {{ $type->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Capacity -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Capacity <span class="text-danger">*</span></label>
                                                                    <select name="units[{{ $index }}][capacity_id]" class="form-control" required>
                                                                        <option value="">Select Capacity</option>
                                                                        @foreach ($capacities as $capacity)
                                                                            <option value="{{ $capacity->id }}" {{ ($unit['capacity_id'] ?? '') == $capacity->id ? 'selected' : '' }}>
                                                                                {{ $capacity->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Model -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Model <span class="text-danger">*</span></label>
                                                                    <select name="units[{{ $index }}][model_id]" class="form-control" required>
                                                                        <option value="">Select Model</option>
                                                                        @foreach ($models as $model)
                                                                            <option value="{{ $model->id }}" {{ ($unit['model_id'] ?? '') == $model->id ? 'selected' : '' }}>
                                                                                {{ $model->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Volt -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Volt <span class="text-danger">*</span></label>
                                                                    <select name="units[{{ $index }}][volt_id]" class="form-control" required>
                                                                        <option value="">Select Volt</option>
                                                                        @foreach ($volts as $volt)
                                                                            <option value="{{ $volt->id }}" {{ ($unit['volt_id'] ?? '') == $volt->id ? 'selected' : '' }}>
                                                                                {{ $volt->value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Disconnect Switch -->
                                                        <div class="form-group">
                                                            <label>Disconnect Switch</label>
                                                            <select name="units[{{ $index }}][disconnect_switch]" class="form-control" data-toggle-field="unit_{{ $index }}_disconnect_details" data-show-on="yes">
                                                                <option value="no" {{ ($unit['disconnect_switch'] ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                                                <option value="yes" {{ ($unit['disconnect_switch'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_disconnect_details" style="display: {{ ($unit['disconnect_switch'] ?? '') == 'yes' ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label>Disconnect Switch Details</label>
                                                                <textarea name="units[{{ $index }}][disconnect_switch_notes]" class="form-control" rows="2">{{ $unit['disconnect_switch_notes'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Disconnect Switch Image</label>
                                                                @if(!empty($unit['disconnect_switch_image']))
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset('storage/' . $unit['disconnect_switch_image']) }}" style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="units[{{ $index }}][disconnect_switch_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="disconnect_{{ $index }}">
                                                                <div id="disconnect_{{ $index }}_preview" class="mt-2"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Cable -->
                                                        <div class="form-group">
                                                            <label>Cable Condition</label>
                                                            <select name="units[{{ $index }}][cable_condition]" class="form-control" data-toggle-field="unit_{{ $index }}_cable_details" data-show-on="good">
                                                                <option value="not_good" {{ ($unit['cable_condition'] ?? 'not_good') == 'not_good' ? 'selected' : '' }}>Not Good</option>
                                                                <option value="good" {{ ($unit['cable_condition'] ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_cable_details" style="display: {{ ($unit['cable_condition'] ?? '') == 'good' ? 'block' : 'none' }}; width: 100% !important;">
                                                            <div class="form-group">
                                                                <label style="width: 100% !important;">Cable Capacity</label>
                                                                <select name="units[{{ $index }}][cable_capacity_id]" class="form-control" style="width: 100% !important;">
                                                                    <option value="">Select Capacity</option>
                                                                    @foreach ($capacities as $capacity)
                                                                        <option value="{{ $capacity->id }}" {{ ($unit['cable_capacity_id'] ?? '') == $capacity->id ? 'selected' : '' }}>
                                                                            {{ $capacity->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Base -->
                                                        <div class="form-group">
                                                            <label>Base Condition</label>
                                                            <select name="units[{{ $index }}][base_condition]" class="form-control" data-toggle-field="unit_{{ $index }}_base_details" data-show-on="good">
                                                                <option value="not_good" {{ ($unit['base_condition'] ?? 'not_good') == 'not_good' ? 'selected' : '' }}>Not Good</option>
                                                                <option value="good" {{ ($unit['base_condition'] ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_base_details" style="display: {{ ($unit['base_condition'] ?? '') == 'good' ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label>Base Notes <span class="text-danger">*</span></label>
                                                                <textarea name="units[{{ $index }}][base_notes]" class="form-control" rows="2">{{ $unit['base_notes'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Base Image <span class="text-danger">*</span></label>
                                                                @if(!empty($unit['base_image']))
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset('storage/' . $unit['base_image']) }}" style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="units[{{ $index }}][base_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="base_{{ $index }}">
                                                                <div id="base_{{ $index }}_preview" class="mt-2"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Duct -->
                                                        <div class="form-group">
                                                            <label>Duct Condition</label>
                                                            <select name="units[{{ $index }}][duct_condition]" class="form-control" data-toggle-field="unit_{{ $index }}_duct_details" data-show-on="good">
                                                                <option value="not_good" {{ ($unit['duct_condition'] ?? 'not_good') == 'not_good' ? 'selected' : '' }}>Not Good</option>
                                                                <option value="good" {{ ($unit['duct_condition'] ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_duct_details" style="display: {{ ($unit['duct_condition'] ?? '') == 'good' ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label>Duct Notes <span class="text-danger">*</span></label>
                                                                <textarea name="units[{{ $index }}][duct_notes]" class="form-control" rows="2">{{ $unit['duct_notes'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Duct Image <span class="text-danger">*</span></label>
                                                                @if(!empty($unit['duct_image']))
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset('storage/' . $unit['duct_image']) }}" style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="units[{{ $index }}][duct_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="duct_{{ $index }}">
                                                                <div id="duct_{{ $index }}_preview" class="mt-2"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Duct Solution -->
                                                        <div class="form-group">
                                                            <label>Duct Solution</label>
                                                            <select name="units[{{ $index }}][duct_solution]" class="form-control">
                                                                <option value="not_good" {{ ($unit['duct_solution'] ?? 'not_good') == 'not_good' ? 'selected' : '' }}>Not Good</option>
                                                                <option value="good" {{ ($unit['duct_solution'] ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Copper Pipe -->
                                                        <div class="form-group">
                                                            <label>Copper Pipe</label>
                                                            <select name="units[{{ $index }}][copper_pipe]" class="form-control" data-toggle-field="unit_{{ $index }}_copper_qty" data-show-on="yes">
                                                                <option value="no" {{ ($unit['copper_pipe'] ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                                                <option value="yes" {{ ($unit['copper_pipe'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_copper_qty" style="display: {{ ($unit['copper_pipe'] ?? '') == 'yes' ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label>Copper Pipe Quantity</label>
                                                                <input type="number" name="units[{{ $index }}][copper_pipe_qty]" class="form-control" min="0" step="0.01" value="{{ $unit['copper_pipe_qty'] ?? '' }}">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Crane -->
                                                        <div class="form-group">
                                                            <label>Crane</label>
                                                            <select name="units[{{ $index }}][crane]" class="form-control" data-toggle-field="unit_{{ $index }}_crane_qty" data-show-on="yes">
                                                                <option value="no" {{ ($unit['crane'] ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                                                <option value="yes" {{ ($unit['crane'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div id="unit_{{ $index }}_crane_qty" style="display: {{ ($unit['crane'] ?? '') == 'yes' ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label>Crane Quantity</label>
                                                                <input type="number" name="units[{{ $index }}][crane_qty]" class="form-control" min="0" step="0.01" value="{{ $unit['crane_qty'] ?? '' }}">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Unit Notes -->
                                                        <div class="form-group">
                                                            <label>Unit Notes</label>
                                                            <textarea name="units[{{ $index }}][notes]" class="form-control" rows="3">{{ $unit['notes'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Project Items Section (for work_completed only) -->
                        <div id="project-items-section" style="display: {{ $report->report_type == 'work_completed' ? 'block' : 'none' }};">
                            <div id="project-items-table-container"></div>
                        </div>

                        <!-- Checklist Section -->
                        <div id="checklist-container" style="display: {{ $report->report_type != 'sites_refer_report' ? 'block' : 'none' }};">
                            <hr>
                            <h5 class="mb-3"
                                style="background-color: #003366; color: white; padding: 10px; text-align: center;">
                                Checklist Items
                            </h5>
                            <div id="checklist-items">
                                @if(!empty($checklistItems))
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background-color: #f0f0f0;">
                                                <tr>
                                                    <th style="width: 50px; text-align: center;">#</th>
                                                    <th>Item</th>
                                                    <th style="text-align: right;">العنصر</th>
                                                    <th style="width: 250px; text-align: center;">Response</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($checklistItems as $index => $item)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                                        <td>{{ $item['en'] }}</td>
                                                        <td style="text-align: right; direction: rtl;">{{ $item['ar'] }}</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                @php
                                                                    $response = $report->checklist_items[$index] ?? 'not_available';
                                                                @endphp
                                                                <div class="custom-control custom-radio mx-2">
                                                                    <input type="radio" class="custom-control-input"
                                                                        id="item_{{ $index }}_yes"
                                                                        name="checklist_items[{{ $index }}]" value="yes"
                                                                        {{ $response == 'yes' ? 'checked' : '' }} required>
                                                                    <label class="custom-control-label"
                                                                        for="item_{{ $index }}_yes">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mx-2">
                                                                    <input type="radio" class="custom-control-input"
                                                                        id="item_{{ $index }}_no"
                                                                        name="checklist_items[{{ $index }}]" value="no"
                                                                        {{ $response == 'no' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="item_{{ $index }}_no">No</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mx-2">
                                                                    <input type="radio" class="custom-control-input"
                                                                        id="item_{{ $index }}_na"
                                                                        name="checklist_items[{{ $index }}]"
                                                                        value="not_available"
                                                                        {{ $response == 'not_available' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="item_{{ $index }}_na">N/A</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Custom Fields Section -->
                        <div id="custom-fields-container" class="custom-fields-section"
                            style="display: {{ (count($customFields) > 0 && $report->report_type != 'sites_refer_report') ? 'block' : 'none' }};">
                            <h5 class="mb-3" style="color: #003366;">
                                <i class="fas fa-list-alt"></i> Additional Information
                            </h5>
                            <div id="custom-fields-content">
                                @if(!empty($customFields))
                                    <div class="row">
                                        @foreach ($customFields as $field)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ $field['label'] }} / {{ $field['label_ar'] }}</label>
                                                @if ($field['type'] === 'textarea')
                                                    <textarea name="custom_fields[{{ $field['name'] }}]" class="form-control" rows="3"
                                                        placeholder="{{ $field['label'] }}">{{ old('custom_fields.' . $field['name'], $report->custom_fields[$field['name']] ?? '') }}</textarea>
                                                @else
                                                    <input type="{{ $field['type'] }}"
                                                        name="custom_fields[{{ $field['name'] }}]" class="form-control"
                                                        placeholder="{{ $field['label'] }}"
                                                        value="{{ old('custom_fields.' . $field['name'], $report->custom_fields[$field['name']] ?? '') }}"
                                                        {{ $field['type'] === 'number' ? 'step=any' : '' }}>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group mt-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Add any additional notes here...">{{ old('notes', $report->notes) }}</textarea>
                        @error('notes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Existing Images -->
                    @if (!empty($report->images) && count($report->images) > 0)
                        <div class="form-group mt-4">
                            <label><strong>Existing Images</strong></label>
                            <div class="image-preview-container" id="existing-images-container">
                                @foreach ($report->images as $image)
                                    <div class="existing-image" data-image="{{ $image }}">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Report Image">
                                        <button type="button" class="delete-image"
                                            onclick="deleteExistingImage(this, '{{ $image }}')">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images Upload -->
                    <div class="form-group mt-4">
                        <label>Upload New Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" accept="image/*"
                            multiple onchange="previewImages(event)">
                        <small class="form-text text-muted">You can upload multiple images (max 5MB each, formats: jpg,
                            png, gif)</small>
                        <div id="image-preview-container" class="image-preview-container"></div>
                        @error('images.*')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Report
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let checklistData = {};
    let customFieldsData = {};
    let isLoadingProjectItems = false;
    let deletedImages = [];

    // Delete existing image
    function deleteExistingImage(button, imagePath) {
        if (confirm('Are you sure you want to delete this image?')) {
            const imageContainer = button.closest('.existing-image');
            imageContainer.remove();

            // Add to delete list
            deletedImages.push(imagePath);

            // Add hidden input to form
            const form = document.querySelector('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imagePath;
            form.appendChild(input);
        }
    }

    // Image preview function for new images
    function previewImages(event) {
        const container = document.getElementById('image-preview-container');
        container.innerHTML = '';

        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-image" onclick="removePreviewImage(${i})">&times;</button>
                `;
                container.appendChild(div);
            };

            reader.readAsDataURL(file);
        }
    }

    function removePreviewImage(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        const files = input.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        input.files = dt.files;
        previewImages({target: input});
    }

    $(document).ready(function() {
        $('.select2').select2();

        // Add new unit - يعد بناءً على الموجود فعلياً
        $(document).on('click', '#add-unit-btn', function() {
            // عد الـ units الموجودة حالياً
            const currentUnitsCount = $('.unit-card').length;
            const unitHtml = generateUnitForm(currentUnitsCount);
            $('#units-container').append(unitHtml);

            // Initialize select2 for the new unit
            $(`.unit-card:last select`).select2();
        });

        // Remove unit
        $(document).on('click', '.remove-unit-btn', function() {
            const unitCard = $(this).closest('.unit-card');
            unitCard.remove();
            
            // إعادة ترقيم الـ units المتبقية
            renumberUnits();
        });

        // إعادة ترقيم الـ units
        function renumberUnits() {
            $('.unit-card').each(function(index) {
                $(this).find('.card-header h6').html(`<i class="fas fa-cube"></i> Unit #${index + 1}`);
            });
        }

        // Toggle fields based on selections
        $(document).on('change', '[data-toggle-field]', function() {
            const target = $(this).data('toggle-field');
            const value = $(this).val();
            const showOn = $(this).data('show-on');

            if (value === showOn) {
                $(`#${target}`).show();
                // إضافة required للحقول المطلوبة
                $(`#${target}`).find('input[type="file"], textarea').prop('required', true);
            } else {
                $(`#${target}`).hide();
                // إزالة required من الحقول المخفية
                $(`#${target}`).find('input, textarea').prop('required', false).val('');
            }
        });

        // Handle image preview for units
        $(document).on('change', '.unit-image-input', function(e) {
            const target = $(this).data('preview-target');
            previewUnitImages(e, target);
        });

        function generateUnitForm(index) {
            return `
        <div class="card unit-card mb-3" data-unit-index="${index}">
            <div class="card-header" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-cube"></i> Unit #${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-unit-btn">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Brand -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Brand <span class="text-danger">*</span></label>
                            <select name="units[${index}][brand_id]" class="form-control" required>
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="units[${index}][type_id]" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Capacity -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Capacity <span class="text-danger">*</span></label>
                            <select name="units[${index}][capacity_id]" class="form-control" required>
                                <option value="">Select Capacity</option>
                                @foreach ($capacities as $capacity)
                                    <option value="{{ $capacity->id }}">{{ $capacity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Model -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Model <span class="text-danger">*</span></label>
                            <select name="units[${index}][model_id]" class="form-control" required>
                                <option value="">Select Model</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Volt -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Volt <span class="text-danger">*</span></label>
                            <select name="units[${index}][volt_id]" class="form-control" required>
                                <option value="">Select Volt</option>
                                @foreach ($volts as $volt)
                                    <option value="{{ $volt->id }}">{{ $volt->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Disconnect Switch -->
                <div class="form-group">
                    <label>Disconnect Switch</label>
                    <select name="units[${index}][disconnect_switch]" class="form-control" data-toggle-field="unit_${index}_disconnect_details" data-show-on="yes">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div id="unit_${index}_disconnect_details" style="display: none;">
                    <div class="form-group">
                        <label>Disconnect Switch Details</label>
                        <textarea name="units[${index}][disconnect_switch_notes]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Disconnect Switch Image</label>
                        <input type="file" name="units[${index}][disconnect_switch_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="disconnect_${index}">
                        <div id="disconnect_${index}_preview" class="mt-2"></div>
                    </div>
                </div>
                
                <!-- Cable -->
                <div class="form-group">
                    <label>Cable Condition</label>
                    <select name="units[${index}][cable_condition]" class="form-control" data-toggle-field="unit_${index}_cable_details" data-show-on="good">
                        <option value="not_good">Not Good</option>
                        <option value="good">Good</option>
                    </select>
                </div>
                <div id="unit_${index}_cable_details" style="display: none;width: 100% !important;">
                    <div class="form-group">
                        <label style="width: 100% !important;">Cable Capacity</label>
                        <select name="units[${index}][cable_capacity_id]" class="form-control" style="width: 100% !important;">
                            <option value="">Select Capacity</option>
                            @foreach ($capacities as $capacity)
                                <option value="{{ $capacity->id }}">{{ $capacity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Base -->
                <div class="form-group">
                    <label>Base Condition</label>
                    <select name="units[${index}][base_condition]" class="form-control" data-toggle-field="unit_${index}_base_details" data-show-on="good">
                        <option value="not_good">Not Good</option>
                        <option value="good">Good</option>
                    </select>
                </div>
                <div id="unit_${index}_base_details" style="display: none;">
                    <div class="form-group">
                        <label>Base Notes <span class="text-danger">*</span></label>
                        <textarea name="units[${index}][base_notes]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Base Image <span class="text-danger">*</span></label>
                        <input type="file" name="units[${index}][base_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="base_${index}">
                        <div id="base_${index}_preview" class="mt-2"></div>
                    </div>
                </div>
                
                <!-- Duct -->
                <div class="form-group">
                    <label>Duct Condition</label>
                    <select name="units[${index}][duct_condition]" class="form-control" data-toggle-field="unit_${index}_duct_details" data-show-on="good">
                        <option value="not_good">Not Good</option>
                        <option value="good">Good</option>
                    </select>
                </div>
                <div id="unit_${index}_duct_details" style="display: none;">
                    <div class="form-group">
                        <label>Duct Notes <span class="text-danger">*</span></label>
                        <textarea name="units[${index}][duct_notes]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Duct Image <span class="text-danger">*</span></label>
                        <input type="file" name="units[${index}][duct_image]" class="form-control unit-image-input" accept="image/*" data-preview-target="duct_${index}">
                        <div id="duct_${index}_preview" class="mt-2"></div>
                    </div>
                </div>
                
                <!-- Duct Solution -->
                <div class="form-group">
                    <label>Duct Solution</label>
                    <select name="units[${index}][duct_solution]" class="form-control">
                        <option value="not_good">Not Good</option>
                        <option value="good">Good</option>
                    </select>
                </div>
                
                <!-- Copper Pipe -->
                <div class="form-group">
                    <label>Copper Pipe</label>
                    <select name="units[${index}][copper_pipe]" class="form-control" data-toggle-field="unit_${index}_copper_qty" data-show-on="yes">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div id="unit_${index}_copper_qty" style="display: none;">
                    <div class="form-group">
                        <label>Copper Pipe Quantity</label>
                        <input type="number" name="units[${index}][copper_pipe_qty]" class="form-control" min="0" step="0.01">
                    </div>
                </div>
                
                <!-- Crane -->
                <div class="form-group">
                    <label>Crane</label>
                    <select name="units[${index}][crane]" class="form-control" data-toggle-field="unit_${index}_crane_qty" data-show-on="yes">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div id="unit_${index}_crane_qty" style="display: none;">
                    <div class="form-group">
                        <label>Crane Quantity</label>
                        <input type="number" name="units[${index}][crane_qty]" class="form-control" min="0" step="0.01">
                    </div>
                </div>
                
                <!-- Unit Notes -->
                <div class="form-group">
                    <label>Unit Notes</label>
                    <textarea name="units[${index}][notes]" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>
    `;
        }

        function previewUnitImages(event, targetId) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                $(`#${targetId}_preview`).html(`
                    <img src="${e.target.result}" style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                `);
            };
            reader.readAsDataURL(file);
        }

        // Auto-fill store when project is selected
        $('#project_amer_id').on('change', function() {
            const projectId = $(this).val();

            if (projectId) {
                $('#store_id').prop('disabled', true);

                $.ajax({
                    url: '{{ route('reports.getProjectStore') }}',
                    type: 'GET',
                    data: { project_id: projectId },
                    success: function(response) {
                        $('#store_id').val(response.store_id).trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading project store:', error);
                    },
                    complete: function() {
                        $('#store_id').prop('disabled', false);
                    }
                });
            }

            loadProjectItems();
        });

        // Load data when report type changes
        $('#report_type').on('change', function() {
            const reportType = $(this).val();

            if (reportType) {
                loadReportTypeData(reportType);
                
                // Show/hide sections based on report type
                if (reportType === 'sites_refer_report') {
                    $('#sites-refer-section').show();
                    $('#store_id').prop('required', false);
                    $('#store-manual-fields').show();
                    $('#project-items-section').hide();
                    $('#checklist-container').hide();
                    $('#custom-fields-container').hide();
                } else if (reportType === 'work_completed') {
                    $('#sites-refer-section').hide();
                    $('#store_id').prop('required', true);
                    $('#store-manual-fields').hide();
                    loadProjectItems();
                } else {
                    $('#sites-refer-section').hide();
                    $('#store_id').prop('required', true);
                    $('#store-manual-fields').hide();
                    $('#project-items-section').hide();
                }
            } else {
                hideAllSections();
            }
        });

        function hideAllSections() {
            $('#custom-fields-container').hide();
            $('#checklist-container').hide();
            $('#project-items-section').hide();
            $('#sites-refer-section').hide();
            $('#store-manual-fields').hide();
            $('#project-items-table-container').empty();
        }

        function loadProjectItems() {
            const projectId = $('#project_amer_id').val();
            const reportType = $('#report_type').val();

            $('#project-items-table-container').empty();
            $('#project-items-section').hide();

            if (!projectId || reportType !== 'work_completed') {
                return;
            }

            if (isLoadingProjectItems) {
                return;
            }

            isLoadingProjectItems = true;

            $('#project-items-table-container').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading project items...</p>
                </div>
            `);
            $('#project-items-section').show();

            $.ajax({
                url: '{{ route('reports.getProjectItems') }}',
                type: 'GET',
                data: { project_id: projectId },
                success: function(response) {
                    if (response.items && response.items.length > 0) {
                        renderProjectItemsTable(response.items);
                    } else {
                        $('#project-items-table-container').html(`
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No items found for this project.
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading project items:', error);
                    $('#project-items-table-container').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Failed to load project items. Please try again.
                        </div>
                    `);
                },
                complete: function() {
                    isLoadingProjectItems = false;
                }
            });
        }

        function renderProjectItemsTable(items) {
            if (!items || items.length === 0) {
                $('#project-items-section').hide();
                return;
            }

            const isMaintenance = items[0].project_dept === 'maintenance';
            const usedItems = @json($report->custom_fields['project_items_used'] ?? []);

            let html = `
                <div class="card mt-4">
                    <div class="card-header" style="background-color: #003366; color: white;">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Project Items - Track Usage</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead style="background-color: #f0f0f0;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Type</th>`;

            if (isMaintenance) {
                html += `<th>Model</th>`;
            } else {
                html += `
                                        <th>Capacity</th>
                                        <th>Volt</th>
                                        <th>Brand</th>`;
            }

            html += `
                                        <th style="width: 100px;">Total Qty</th>
                                        <th style="width: 150px;">Used Qty</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            items.forEach((item, index) => {
                html += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>${item.type}</td>`;

                if (isMaintenance) {
                    html += `<td>${item.model}</td>`;
                } else {
                    html += `
                        <td>${item.capacity}</td>
                        <td>${item.volt}</td>
                        <td>${item.brand}</td>`;
                }

                html += `
                        <td class="text-center"><strong class="badge badge-primary">${item.qty}</strong></td>
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="project_items_used[${item.id}]" 
                                   min="0" 
                                   max="${item.qty}" 
                                   value="${usedItems[item.id] || 0}"
                                   placeholder="0"
                                   onchange="validateQty(this, ${item.qty})">
                        </td>
                    </tr>`;
            });

            html += `
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Enter the quantity used for each item (max: available quantity)
                        </small>
                    </div>
                </div>`;

            $('#project-items-table-container').html(html);
        }

        window.validateQty = function(input, maxQty) {
            const value = parseInt(input.value) || 0;
            if (value < 0) {
                input.value = 0;
            } else if (value > maxQty) {
                input.value = maxQty;
                alert(`Maximum available quantity is ${maxQty}`);
            }
        }

        function loadReportTypeData(reportType) {
            if (reportType === 'sites_refer_report') {
                return; // لا نحتاج checklist أو custom fields
            }

            $('#custom-fields-container').hide();
            $('#checklist-container').hide();

            $.ajax({
                url: '{{ route('reports.getReportTypeData') }}',
                type: 'GET',
                data: { report_type: reportType },
                success: function(response) {
                    checklistData[reportType] = response.checklist_items;
                    customFieldsData[reportType] = response.custom_fields;
                    updateFormFields(reportType);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading report type data:', error);
                    alert('Error loading report data. Please try again.');
                }
            });
        }

        function updateFormFields(reportType) {
            const fields = (customFieldsData[reportType] || []).filter(f => f.type !== 'json');

            if (fields.length > 0) {
                renderCustomFields(fields);
                $('#custom-fields-container').show();
            } else {
                $('#custom-fields-container').hide();
            }

            if (checklistData[reportType] && checklistData[reportType].length > 0) {
                renderChecklist(checklistData[reportType]);
                $('#checklist-container').show();
            } else {
                $('#checklist-container').hide();
            }
        }

        function renderCustomFields(fields) {
            const oldValues = @json(old('custom_fields', $report->custom_fields ?? []));
            let html = '<div class="row">';

            fields.forEach(field => {
                const fieldValue = oldValues[field.name] || '';

                html += `
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>${field.label} / ${field.label_ar}</label>`;

                if (field.type === 'textarea') {
                    html += `<textarea name="custom_fields[${field.name}]" 
                                      class="form-control" rows="3" 
                                      placeholder="${field.label}">${fieldValue}</textarea>`;
                } else {
                    html += `<input type="${field.type}" 
                                    name="custom_fields[${field.name}]" 
                                    class="form-control" 
                                    placeholder="${field.label}"
                                    value="${fieldValue}"
                                    ${field.type === 'number' ? 'step="any"' : ''}>`;
                }

                html += `
                        </div>
                    </div>`;
            });

            html += '</div>';
            $('#custom-fields-content').html(html);
        }

        function renderChecklist(items) {
            const oldChecklist = @json($report->checklist_items ?? []);

            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Item</th>
                                <th style="text-align: right;">العنصر</th>
                                <th style="width: 250px; text-align: center;">Response</th>
                            </tr>
                        </thead>
                        <tbody>`;

            items.forEach((item, index) => {
                const response = oldChecklist[index] || 'not_available';

                html += `
                    <tr>
                        <td style="text-align: center;">${index + 1}</td>
                        <td>${item.en}</td>
                        <td style="text-align: right; direction: rtl;">${item.ar}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" class="custom-control-input" 
                                           id="item_${index}_yes" 
                                           name="checklist_items[${index}]" 
                                           value="yes" 
                                           ${response === 'yes' ? 'checked' : ''} required>
                                    <label class="custom-control-label" for="item_${index}_yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" class="custom-control-input" 
                                           id="item_${index}_no" 
                                           name="checklist_items[${index}]" 
                                           value="no"
                                           ${response === 'no' ? 'checked' : ''}>
                                    <label class="custom-control-label" for="item_${index}_no">No</label>
                                </div>
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" class="custom-control-input" 
                                           id="item_${index}_na" 
                                           name="checklist_items[${index}]" 
                                           value="not_available"
                                           ${response === 'not_available' ? 'checked' : ''}>
                                    <label class="custom-control-label" for="item_${index}_na">N/A</label>
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });

            html += `
                        </tbody>
                    </table>
                </div>`;

            $('#checklist-items').html(html);
        }

        // Load project items on page load if report type is work_completed
        if ($('#report_type').val() === 'work_completed' && $('#project_amer_id').val()) {
            loadProjectItems();
        }
    });
</script>
@endsection
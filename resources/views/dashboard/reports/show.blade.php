<!-- resources/views/dashboard/reports/show.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Report Details
@endsection

@section('css')
    <style>
        .report-images {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }

        .report-image-item {
            position: relative;
            width: 200px;
            height: 200px;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .report-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .report-image-item img:hover {
            transform: scale(1.05);
        }

        .custom-fields-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .custom-field-item {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-left: 4px solid #003366;
            border-radius: 4px;
        }

        .unit-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .unit-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #003366;
            padding: 15px;
        }

        .unit-detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .unit-detail-row:last-child {
            border-bottom: none;
        }

        .unit-detail-label {
            font-weight: 600;
            color: #003366;
        }

        .unit-image-preview {
            max-width: 300px;
            max-height: 300px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        .badge-condition {
            font-size: 14px;
            padding: 8px 15px;
        }
    </style>
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Report Details
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mg-b-0">
                        {{ $report->getReportTypeName() }}
                    </h4>
                    <div>
                        @can('download_report')
                        <a href="{{ route('reports.download-pdf', $report->id) }}" class="btn btn-success mr-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                        @endcan
                        @can('edit_report')
                        <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-primary mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Report Header Info -->
                    <div class="row mb-4"
                        style="background-color: #003366; color: white; padding: 15px; border-radius: 5px;">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Report Date:</strong> {{ $report->report_date->format('d/m/Y') }}</p>
                            @if ($report->report_type == 'sites_refer_report')
                                <p class="mb-2"><strong>Store Name:</strong> {{ $report->store_name ?? '—' }}</p>
                                <p class="mb-0"><strong>City:</strong> {{ $report->store_city ?? '—' }}</p>
                            @else
                                <p class="mb-2"><strong>Store Name:</strong> {{ $report->store->name ?? '—' }}</p>
                                <p class="mb-2"><strong>Store UUID:</strong> {{ $report->store->uuid ?? '—' }}</p>
                                <p class="mb-0"><strong>City:</strong> {{ $report->store->city ?? '—' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($report->report_type != 'sites_refer_report')
                                <p class="mb-2"><strong>Store Email:</strong> {{ $report->store->email ?? '—' }}</p>
                            @endif
                            <p class="mb-0"><strong>Project:</strong> {{ $report->projectAmer->po_num ?? '—' }}</p>
                        </div>
                    </div>

                    <!-- Units Section for Sites Refer Report -->
                    @if ($report->report_type == 'sites_refer_report' && isset($report->units) && count($report->units) > 0)
                        <div class="mb-4">
                            <h5
                                style="background-color: #003366; color: white; padding: 10px; text-align: center; border-radius: 5px;">
                                <i class="fas fa-cubes"></i> Units Information
                            </h5>
                            @foreach ($report->units as $index => $unit)
                                <div class="unit-card mt-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-cube"></i> Unit #{{ $index + 1 }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Basic Unit Info -->
                                            <div class="col-md-6">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Brand:</span>
                                                    @php
                                                        $brand_id = $unit['brand_id'];
                                                        if ($brand_id) {
                                                            $brand = App\Models\Brand::find($brand_id);
                                                        }
                                                    @endphp
                                                    <span class="ml-2">{{ $brand->name ?? '—' }}</span>
                                                </div>
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Type:</span>
                                                    @php
                                                        $type_id = $unit['type_id'];
                                                        if ($type_id) {
                                                            $type = App\Models\ProjectType::find($type_id);
                                                        }
                                                    @endphp
                                                    <span class="ml-2">{{ $type->name ?? '—' }}</span>
                                                </div>
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Capacity:</span>
                                                    @php
                                                        $capacity_id = $unit['capacity_id'];
                                                        if ($capacity_id) {
                                                            $capacity = App\Models\ProjectCapacity::find($capacity_id);
                                                        }
                                                    @endphp
                                                    <span class="ml-2">{{ $capacity->name ?? '—' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Model:</span>
                                                    @php
                                                        $model_id = $unit['model_id'];
                                                        if ($model_id) {
                                                            $model = App\Models\ProjectModel::find($model_id);
                                                        }
                                                    @endphp
                                                    <span class="ml-2">{{ $model->name ?? '—' }}</span>
                                                </div>
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Volt:</span>
                                                    @php
                                                        $volt_id = $unit['volt_id'];
                                                        if ($volt_id) {
                                                            $volt = App\Models\ProjectVolt::find($volt_id);
                                                        }
                                                    @endphp
                                                    <span class="ml-2">{{ $volt->value ?? '—' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Disconnect Switch -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Disconnect Switch:</span>
                                                    @if (($unit['disconnect_switch'] ?? 'no') == 'yes')
                                                        <span class="badge badge-success badge-condition ml-2">Yes</span>
                                                    @else
                                                        <span class="badge badge-secondary badge-condition ml-2">No</span>
                                                    @endif
                                                </div>
                                                @if (($unit['disconnect_switch'] ?? '') == 'yes')
                                                    @if (!empty($unit['disconnect_switch_notes']))
                                                        <div class="mt-2 p-2"
                                                            style="background-color: #f8f9fa; border-radius: 5px;">
                                                            <strong>Details:</strong>
                                                            <p class="mb-0">{{ $unit['disconnect_switch_notes'] }}</p>
                                                        </div>
                                                    @endif
                                                    @if (!empty($unit['disconnect_switch_image']))
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $unit['disconnect_switch_image']) }}"
                                                                class="unit-image-preview" alt="Disconnect Switch"
                                                                onclick="window.open(this.src, '_blank')">
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Cable Condition -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Cable Condition:</span>
                                                    @if (($unit['cable_condition'] ?? 'not_good') == 'good')
                                                        <span class="badge badge-success badge-condition ml-2">Good</span>
                                                    @else
                                                        <span class="badge badge-danger badge-condition ml-2">Not
                                                            Good</span>
                                                    @endif
                                                </div>
                                                @if (($unit['cable_condition'] ?? '') == 'good' && !empty($unit['cable_capacity_id']))
                                                    <div class="mt-2 p-2"
                                                        style="background-color: #f8f9fa; border-radius: 5px;">
                                                        @php
                                                            $cable_capacity_id = $unit['cable_capacity_id'];
                                                            if ($cable_capacity_id) {
                                                                $cable_capacity = App\Models\ProjectCapacity::find(
                                                                    $cable_capacity_id,
                                                                );
                                                            }
                                                        @endphp
                                                        <strong>Cable Capacity:</strong> {{ $cable_capacity->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Base Condition -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Base Condition:</span>
                                                    @if (($unit['base_condition'] ?? 'not_good') == 'good')
                                                        <span class="badge badge-success badge-condition ml-2">Good</span>
                                                    @else
                                                        <span class="badge badge-danger badge-condition ml-2">Not
                                                            Good</span>
                                                    @endif
                                                </div>
                                                @if (($unit['base_condition'] ?? '') == 'good')
                                                    @if (!empty($unit['base_notes']))
                                                        <div class="mt-2 p-2"
                                                            style="background-color: #f8f9fa; border-radius: 5px;">
                                                            <strong>Notes:</strong>
                                                            <p class="mb-0">{{ $unit['base_notes'] }}</p>
                                                        </div>
                                                    @endif
                                                    @if (!empty($unit['base_image']))
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $unit['base_image']) }}"
                                                                class="unit-image-preview" alt="Base"
                                                                onclick="window.open(this.src, '_blank')">
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Duct Condition -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Duct Condition:</span>
                                                    @if (($unit['duct_condition'] ?? 'not_good') == 'good')
                                                        <span class="badge badge-success badge-condition ml-2">Good</span>
                                                    @else
                                                        <span class="badge badge-danger badge-condition ml-2">Not
                                                            Good</span>
                                                    @endif
                                                </div>
                                                @if (($unit['duct_condition'] ?? '') == 'good')
                                                    @if (!empty($unit['duct_notes']))
                                                        <div class="mt-2 p-2"
                                                            style="background-color: #f8f9fa; border-radius: 5px;">
                                                            <strong>Notes:</strong>
                                                            <p class="mb-0">{{ $unit['duct_notes'] }}</p>
                                                        </div>
                                                    @endif
                                                    @if (!empty($unit['duct_image']))
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $unit['duct_image']) }}"
                                                                class="unit-image-preview" alt="Duct"
                                                                onclick="window.open(this.src, '_blank')">
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Duct Solution -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Duct Solution:</span>
                                                    @if (($unit['duct_solution'] ?? 'not_good') == 'good')
                                                        <span class="badge badge-success badge-condition ml-2">Good</span>
                                                    @else
                                                        <span class="badge badge-danger badge-condition ml-2">Not
                                                            Good</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Copper Pipe -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Copper Pipe:</span>
                                                    @if (($unit['copper_pipe'] ?? 'no') == 'yes')
                                                        <span class="badge badge-success badge-condition ml-2">Yes</span>
                                                    @else
                                                        <span class="badge badge-secondary badge-condition ml-2">No</span>
                                                    @endif
                                                </div>
                                                @if (($unit['copper_pipe'] ?? '') == 'yes' && !empty($unit['copper_pipe_qty']))
                                                    <div class="mt-2 p-2"
                                                        style="background-color: #f8f9fa; border-radius: 5px;">
                                                        <strong>Quantity:</strong> {{ $unit['copper_pipe_qty'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Crane -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="unit-detail-row">
                                                    <span class="unit-detail-label">Crane:</span>
                                                    @if (($unit['crane'] ?? 'no') == 'yes')
                                                        <span class="badge badge-success badge-condition ml-2">Yes</span>
                                                    @else
                                                        <span class="badge badge-secondary badge-condition ml-2">No</span>
                                                    @endif
                                                </div>
                                                @if (($unit['crane'] ?? '') == 'yes' && !empty($unit['crane_qty']))
                                                    <div class="mt-2 p-2"
                                                        style="background-color: #f8f9fa; border-radius: 5px;">
                                                        <strong>Quantity:</strong> {{ $unit['crane_qty'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Unit Notes -->
                                        @if (!empty($unit['notes']))
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">
                                                        <strong><i class="fas fa-sticky-note"></i> Unit Notes:</strong>
                                                        <p class="mb-0 mt-2">{{ $unit['notes'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Project Items Section (for work_completed) -->
                     @if($report->report_type == 'work_completed' && !empty($report->custom_fields['project_items_used']))
                        @php
                            // Get project items with full details
                            $projectItems = [];
                            if($report->projectAmer) {
                                $usedItemIds = array_keys($report->custom_fields['project_items_used']);
                                $projectItems = \App\Models\ProjectAmerItem::whereIn('id', $usedItemIds)
                                    ->with(['projectType', 'projectModel', 'projectCapacity', 'projectVolt', 'brand'])
                                    ->get()
                                    ->keyBy('id');
                            }
                            $isMaintenance = $report->projectAmer && $report->projectAmer->dept === 'maintenance';
                        @endphp
                        
                        <div class="mb-4">
                            <h5 style="background-color: #003366; color: white; padding: 10px; text-align: center; border-radius: 5px;">
                                <i class="fas fa-list"></i> Project Items Used
                            </h5>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-hover">
                                    <thead style="background-color: #f0f0f0;">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Type</th>
                                            @if($isMaintenance)
                                                <th>Model</th>
                                            @else
                                                <th>Capacity</th>
                                                <th>Volt</th>
                                                <th>Brand</th>
                                            @endif
                                            <th style="width: 100px; text-align: center;">Total Qty</th>
                                            <th style="width: 150px; text-align: center;">Quantity Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($report->custom_fields['project_items_used'] as $itemId => $qty)
                                            @if($qty > 0)
                                                @php
                                                    $item = $projectItems[$itemId] ?? null;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->projectType->name ?? '—' }}</td>
                                                    @if($isMaintenance)
                                                        <td>{{ $item->projectModel->name ?? '—' }}</td>
                                                    @else
                                                        <td>{{ $item->projectCapacity->name ?? '—' }}</td>
                                                        <td>{{ $item->projectVolt->value ?? '—' }}</td>
                                                        <td>{{ $item->brand->name ?? '—' }}</td>
                                                    @endif
                                                    <td class="text-center">
                                                        <strong class="badge badge-info" style="font-size: 14px; padding: 8px 15px;">
                                                            {{ $item->qty ?? 0 }}
                                                        </strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-primary" style="font-size: 14px; padding: 8px 15px;">
                                                            {{ $qty }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Checklist Items -->
                    @if ($report->report_type != 'sites_refer_report' && !empty($checklistItems))
                        <h5
                            style="background-color: #003366; color: white; padding: 10px; text-align: center; border-radius: 5px;">
                            <i class="fas fa-check-square"></i> Checklist Items
                        </h5>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead style="background-color: #f0f0f0;">
                                    <tr>
                                        <th style="width: 50px; text-align: center;">#</th>
                                        <th>Item</th>
                                        <th style="text-align: right;">العنصر</th>
                                        <th style="width: 150px; text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checklistItems as $index => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $index + 1 }}</td>
                                            <td>{{ $item['en'] }}</td>
                                            <td style="text-align: right; direction: rtl;">{{ $item['ar'] }}</td>
                                            <td style="text-align: center;">
                                                @php
                                                    $response = $report->checklist_items[$index] ?? 'not_available';
                                                @endphp
                                                @if ($response === 'yes')
                                                    <span class="badge badge-success badge-condition">
                                                        <i class="fas fa-check"></i> Yes
                                                    </span>
                                                @elseif ($response === 'no')
                                                    <span class="badge badge-danger badge-condition">
                                                        <i class="fas fa-times"></i> No
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary badge-condition">
                                                        <i class="fas fa-minus"></i> N/A
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Custom Fields Section -->
                    @if (!empty($report->custom_fields) && count($customFields) > 0)
                        @php
                            $hasVisibleFields = false;
                            foreach ($customFields as $field) {
                                if (
                                    isset($report->custom_fields[$field['name']]) &&
                                    $report->custom_fields[$field['name']] &&
                                    $field['name'] !== 'project_items_used'
                                ) {
                                    $hasVisibleFields = true;
                                    break;
                                }
                            }
                        @endphp

                        @if ($hasVisibleFields)
                            <div class="custom-fields-section">
                                <h5 class="mb-3" style="color: #003366;">
                                    <i class="fas fa-list-alt"></i> Additional Information
                                </h5>
                                <div class="row">
                                    @foreach ($customFields as $field)
                                        @if (isset($report->custom_fields[$field['name']]) &&
                                                $report->custom_fields[$field['name']] &&
                                                $field['name'] !== 'project_items_used')
                                            <div class="col-md-6">
                                                <div class="custom-field-item">
                                                    <strong>{{ $field['label'] }}</strong> /
                                                    <strong
                                                        style="direction: rtl; display: inline-block;">{{ $field['label_ar'] }}</strong>
                                                    <p class="mb-0 mt-2" style="font-size: 16px; color: #333;">
                                                        {{ $report->custom_fields[$field['name']] }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Notes Section -->
                    @if ($report->notes)
                        <div class="mt-4">
                            <h5 style="background-color: #003366; color: white; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-sticky-note"></i> Notes
                            </h5>
                            <div class="alert alert-info mt-3">
                                {{ $report->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- Images Section -->
                    @if (!empty($report->images) && count($report->images) > 0)
                        <div class="mt-4">
                            <h5 style="background-color: #003366; color: white; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-images"></i> Report Images
                            </h5>
                            <div class="report-images">
                                @foreach ($report->images as $image)
                                    <div class="report-image-item">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Report Image"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Footer Info -->
                    <div class="row mt-4 pt-3" style="border-top: 2px solid #dee2e6;">
                        <div class="col-md-6">
                            <p><strong>Created By:</strong> {{ $report->creator->name ?? '—' }}</p>
                            <p><strong>Created At:</strong> {{ $report->created_at->format('d/m/Y H:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Last Updated:</strong> {{ $report->updated_at->format('d/m/Y H:i A') }}</p>
                            <p><strong>Report ID:</strong> #{{ $report->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

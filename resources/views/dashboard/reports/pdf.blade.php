<!-- resources/views/dashboard/reports/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $report->getReportTypeName() }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            padding: 20px;
            font-size: 11px;
        }
        
        .container {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
            text-align: end;
        }

        .logo {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
        }

        .header-type {
            background-color: #003366;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header-type h1.title {
            text-align: center;
            font-size: 18px;
            margin: 0px;
            font-weight: bold;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .info-table th,
        .info-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        .info-table .table-header {
            background-color: #b8d4e8;
            font-weight: bold;
            text-align: center !important;
            padding: 10px;
            font-size: 14px;
        }
        
        .info-table .label-cell {
            font-weight: bold;
            background-color: #e8f4f8;
            width: 25%;
        }

        .section-header {
            background-color: #003366;
            color: white;
            padding: 10px;
            text-align: center;
            margin: 20px 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        /* Unit Cards Styles */
        .unit-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .unit-card {
            border: 2px solid #333;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .unit-header {
            background-color: #b8d4e8;
            padding: 10px;
            font-weight: bold;
            font-size: 13px;
            border-bottom: 2px solid #333;
        }

        .unit-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .unit-details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        .unit-details-table .label {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 30%;
        }

        .condition-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        .badge-success {
            background-color: #4CAF50;
            color: white;
        }

        .badge-danger {
            background-color: #f44336;
            color: white;
        }

        .badge-secondary {
            background-color: #999;
            color: white;
        }

        .unit-note-box {
            background-color: #f8f9fa;
            padding: 8px;
            margin-top: 5px;
            border-left: 3px solid #003366;
            font-size: 10px;
        }

        /* Project Items Table */
        .project-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .project-items-table th,
        .project-items-table td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 11px;
        }

        .project-items-table th {
            background-color: #b8d4e8;
            font-weight: bold;
            text-align: center;
        }

        .project-items-table td:first-child {
            width: 40px;
            text-align: center;
            font-weight: bold;
        }

        .qty-badge {
            display: inline-block;
            background-color: #003366;
            color: white;
            padding: 4px 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        /* Checklist Table */
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .checklist-table th,
        .checklist-table td {
            border: 1px solid #333;
            padding: 8px;
        }
        
        .checklist-table th {
            background-color: #b8d4e8;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        
        .checklist-table td {
            font-size: 10px;
        }
        
        .checklist-table td:first-child {
            width: 40px;
            text-align: center;
            font-weight: bold;
        }
        
        .checklist-table td:nth-child(2) {
            width: 40%;
        }
        
        .checklist-table td:nth-child(3) {
            width: 35%;
            text-align: right;
        }
        
        .checklist-table td:nth-child(4) {
            width: 120px;
            text-align: center;
        }
        
        .status-boxes {
            display: inline-block;
            white-space: nowrap;
        }
        
        .status-box {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #333;
            margin: 0 3px;
            vertical-align: middle;
            position: relative;
        }
        
        .status-checked {
            background-color: #4CAF50;
        }
        
        .status-checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 16px;
            font-weight: bold;
            left: 2px;
            top: -2px;
        }

        /* Custom Fields */
        .custom-fields-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .custom-field-row {
            border: 1px solid #ddd;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .custom-field-label {
            font-weight: bold;
            color: #003366;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .custom-field-value {
            font-size: 11px;
            line-height: 1.5;
        }
        
        /* Notes Section */
        .notes-section {
            margin-top: 20px;
            padding: 12px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
        
        .notes-section h3 {
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .notes-section p {
            line-height: 1.6;
            font-size: 11px;
        }
        
        /* Signature Section */
        .signature-section {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-row {
            width: 100%;
            margin-bottom: 30px;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            padding: 5px 10px;
            vertical-align: middle;
        }

        .signature-label-cell {
            width: 30%;
            font-weight: bold;
            font-size: 12px;
        }

        .signature-line-cell {
            width: 70%;
            border-bottom: 1px dotted #333;
            padding-bottom: 2px;
        }

        .signature-columns {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .signature-columns td {
            width: 50%;
            border: none;
            vertical-align: top;
            padding: 0 10px;
        }

        .stamp-box {
            width: 100%;
            height: 100px;
            border: 2px dashed #999;
            margin-top: 20px;
            text-align: center;
            padding-top: 40px;
            font-weight: bold;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_asset('dashboard/assets/img/brand/toggle-logo.png') }}" alt="Logo" class="logo">
        </div>
        
        <div class="header-type">
            <h1 class="title">{{ $report->getReportTypeName() }}</h1>
        </div>

        <!-- Report Information -->
        <table class="info-table">
            <tr>
                <th colspan="4" class="table-header">REPORT INFORMATION</th>
            </tr>
            <tr>
                <td class="label-cell">Report Date</td>
                <td>{{ $report->report_date->format('d/m/Y') }}</td>
                <td class="label-cell">Report ID</td>
                <td>#{{ $report->id }}</td>
            </tr>
            <tr>
                <td class="label-cell">Store Name</td>
                <td>
                    @if($report->report_type == 'sites_refer_report')
                        {{ $report->store_name ?? '—' }}
                    @else
                        {{ $report->store->name ?? '—' }}
                    @endif
                </td>
                <td class="label-cell">Store ID</td>
                <td>
                    @if($report->report_type == 'sites_refer_report')
                        —
                    @else
                        {{ $report->store->uuid ?? '—' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-cell">City</td>
                <td>
                    @if($report->report_type == 'sites_refer_report')
                        {{ $report->store_city ?? '—' }}
                    @else
                        {{ $report->store->city ?? '—' }}
                    @endif
                </td>
                <td class="label-cell">Project</td>
                <td>{{ $report->projectAmer->po_num ?? '—' }}</td>
            </tr>
            @if($report->report_type != 'sites_refer_report')
                <tr>
                    <td class="label-cell">Store Email</td>
                    <td colspan="3">{{ $report->store->email ?? '—' }}</td>
                </tr>
            @endif
        </table>

        <!-- Units Section (for Sites Refer Report) -->
        @if($report->report_type == 'sites_refer_report' && isset($report->units) && count($report->units) > 0)
            <div class="section-header">UNITS INFORMATION</div>
            
            @foreach($report->units as $index => $unit)
                <div class="unit-card">
                    <div class="unit-header">Unit #{{ $index + 1 }}</div>
                    
                    <table class="unit-details-table">
                        <!-- Basic Info -->
                        <tr>
                            <td class="label">Brand</td>
                            <td>
                                @php
                                    $brand = isset($unit['brand_id']) ? \App\Models\Brand::find($unit['brand_id']) : null;
                                @endphp
                                {{ $brand->name ?? '—' }}
                            </td>
                            <td class="label">Type</td>
                            <td>
                                @php
                                    $type = isset($unit['type_id']) ? \App\Models\ProjectType::find($unit['type_id']) : null;
                                @endphp
                                {{ $type->name ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Capacity</td>
                            <td>
                                @php
                                    $capacity = isset($unit['capacity_id']) ? \App\Models\ProjectCapacity::find($unit['capacity_id']) : null;
                                @endphp
                                {{ $capacity->name ?? '—' }}
                            </td>
                            <td class="label">Model</td>
                            <td>
                                @php
                                    $model = isset($unit['model_id']) ? \App\Models\ProjectModel::find($unit['model_id']) : null;
                                @endphp
                                {{ $model->name ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Volt</td>
                            <td colspan="3">
                                @php
                                    $volt = isset($unit['volt_id']) ? \App\Models\ProjectVolt::find($unit['volt_id']) : null;
                                @endphp
                                {{ $volt->value ?? '—' }}
                            </td>
                        </tr>

                        <!-- Disconnect Switch -->
                        <tr>
                            <td class="label">Disconnect Switch</td>
                            <td colspan="3">
                                @if(($unit['disconnect_switch'] ?? 'no') == 'yes')
                                    <span class="condition-badge badge-success">YES</span>
                                    @if(!empty($unit['disconnect_switch_notes']))
                                        <div class="unit-note-box">{{ $unit['disconnect_switch_notes'] }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-secondary">NO</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Cable Condition -->
                        <tr>
                            <td class="label">Cable Condition</td>
                            <td colspan="3">
                                @if(($unit['cable_condition'] ?? 'not_good') == 'good')
                                    <span class="condition-badge badge-success">GOOD</span>
                                    @if(!empty($unit['cable_capacity_id']))
                                        @php
                                            $cableCapacity = \App\Models\ProjectCapacity::find($unit['cable_capacity_id']);
                                        @endphp
                                        <div class="unit-note-box"><strong>Cable Capacity:</strong> {{ $cableCapacity->name ?? '—' }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-danger">NOT GOOD</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Base Condition -->
                        <tr>
                            <td class="label">Base Condition</td>
                            <td colspan="3">
                                @if(($unit['base_condition'] ?? 'not_good') == 'good')
                                    <span class="condition-badge badge-success">GOOD</span>
                                    @if(!empty($unit['base_notes']))
                                        <div class="unit-note-box">{{ $unit['base_notes'] }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-danger">NOT GOOD</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Duct Condition -->
                        <tr>
                            <td class="label">Duct Condition</td>
                            <td colspan="3">
                                @if(($unit['duct_condition'] ?? 'not_good') == 'good')
                                    <span class="condition-badge badge-success">GOOD</span>
                                    @if(!empty($unit['duct_notes']))
                                        <div class="unit-note-box">{{ $unit['duct_notes'] }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-danger">NOT GOOD</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Duct Solution -->
                        <tr>
                            <td class="label">Duct Solution</td>
                            <td colspan="3">
                                @if(($unit['duct_solution'] ?? 'not_good') == 'good')
                                    <span class="condition-badge badge-success">GOOD</span>
                                @else
                                    <span class="condition-badge badge-danger">NOT GOOD</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Copper Pipe -->
                        <tr>
                            <td class="label">Copper Pipe</td>
                            <td colspan="3">
                                @if(($unit['copper_pipe'] ?? 'no') == 'yes')
                                    <span class="condition-badge badge-success">YES</span>
                                    @if(!empty($unit['copper_pipe_qty']))
                                        <div class="unit-note-box"><strong>Quantity:</strong> {{ $unit['copper_pipe_qty'] }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-secondary">NO</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Crane -->
                        <tr>
                            <td class="label">Crane</td>
                            <td colspan="3">
                                @if(($unit['crane'] ?? 'no') == 'yes')
                                    <span class="condition-badge badge-success">YES</span>
                                    @if(!empty($unit['crane_qty']))
                                        <div class="unit-note-box"><strong>Quantity:</strong> {{ $unit['crane_qty'] }}</div>
                                    @endif
                                @else
                                    <span class="condition-badge badge-secondary">NO</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Unit Notes -->
                        @if(!empty($unit['notes']))
                            <tr>
                                <td class="label">Notes</td>
                                <td colspan="3">
                                    <div class="unit-note-box">{{ $unit['notes'] }}</div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

                @if($index < count($report->units) - 1)
                    <div class="page-break"></div>
                @endif
            @endforeach
        @endif

        <!-- Project Items Section (for Work Completed) -->
        @if($report->report_type == 'work_completed' && !empty($report->custom_fields['project_items_used']))
            <div class="section-header">PROJECT ITEMS USED</div>

            @php
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

            <table class="project-items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        @if($isMaintenance)
                            <th>Model</th>
                        @else
                            <th>Capacity</th>
                            <th>Volt</th>
                            <th>Brand</th>
                        @endif
                        <th>Total Qty</th>
                        <th>Used Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @php $itemNumber = 1; @endphp
                    @foreach($report->custom_fields['project_items_used'] as $itemId => $qty)
                        @if($qty > 0)
                            @php
                                $item = $projectItems[$itemId] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $itemNumber++ }}</td>
                                <td>{{ $item->projectType->name ?? '—' }}</td>
                                @if($isMaintenance)
                                    <td>{{ $item->projectModel->name ?? '—' }}</td>
                                @else
                                    <td>{{ $item->projectCapacity->name ?? '—' }}</td>
                                    <td>{{ $item->projectVolt->value ?? '—' }}</td>
                                    <td>{{ $item->brand->name ?? '—' }}</td>
                                @endif
                                <td style="text-align: center;">
                                    <span class="qty-badge">{{ $item->qty ?? 0 }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="qty-badge">{{ $qty }}</span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
        
        <!-- Checklist Section (not for Sites Refer Report) -->
        @if($report->report_type != 'sites_refer_report' && !empty($checklistItems))
            <div class="section-header">CHECKLIST ITEMS</div>

            <table class="checklist-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th style="text-align: right;">العنصر</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($checklistItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['en'] }}</td>
                            <td style="text-align: right;">{{ $item['ar'] }}</td>
                            <td>
                                <div class="status-boxes">
                                    @php
                                        $response = $report->checklist_items[$index] ?? 'not_available';
                                    @endphp
                                    <span class="status-box {{ $response === 'yes' ? 'status-checked' : '' }}"></span>
                                    <span class="status-box {{ $response === 'no' ? 'status-checked' : '' }}"></span>
                                    <span class="status-box {{ $response === 'not_available' ? 'status-checked' : '' }}"></span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Custom Fields Section (not for Sites Refer Report) -->
        @if($report->report_type != 'sites_refer_report' && !empty($report->custom_fields) && count($customFields) > 0)
            @php
                $hasVisibleFields = false;
                foreach ($customFields as $field) {
                    if (isset($report->custom_fields[$field['name']]) && 
                        $report->custom_fields[$field['name']] && 
                        $field['name'] !== 'project_items_used') {
                        $hasVisibleFields = true;
                        break;
                    }
                }
            @endphp

            @if($hasVisibleFields)
                <div class="section-header">ADDITIONAL INFORMATION</div>

                <div class="custom-fields-section">
                    @foreach($customFields as $field)
                        @if(isset($report->custom_fields[$field['name']]) && 
                            $report->custom_fields[$field['name']] && 
                            $field['name'] !== 'project_items_used')
                            <div class="custom-field-row">
                                <div class="custom-field-label">
                                    {{ $field['label'] }} / {{ $field['label_ar'] }}
                                </div>
                                <div class="custom-field-value">
                                    {{ $report->custom_fields[$field['name']] }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Notes Section -->
        @if($report->notes)
            <div class="notes-section">
                <h3>Additional Notes:</h3>
                <p>{{ $report->notes }}</p>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="signature-columns">
                <tr>
                    <td>
                        <table class="signature-table">
                            <tr>
                                <td class="signature-label-cell">Name:</td>
                                <td class="signature-line-cell"></td>
                            </tr>
                            <tr>
                                <td class="signature-label-cell">Signature:</td>
                                <td class="signature-line-cell"></td>
                            </tr>
                            <tr>
                                <td class="signature-label-cell">Mobile number:</td>
                                <td class="signature-line-cell"></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="signature-table">
                            <tr>
                                <td class="signature-label-cell">Technician name:</td>
                                <td class="signature-line-cell"></td>
                            </tr>
                            <tr>
                                <td class="signature-label-cell">Signature:</td>
                                <td class="signature-line-cell"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="stamp-box">STAMP</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin-top: 30px; padding-top: 10px; border-top: 2px solid #333; font-size: 10px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; width: 50%;">
                        <strong>Created By:</strong> {{ $report->creator->name ?? '—' }}<br>
                        <strong>Created At:</strong> {{ $report->created_at->format('d/m/Y H:i A') }}
                    </td>
                    <td style="border: none; width: 50%; text-align: right;">
                        <strong>Last Updated:</strong> {{ $report->updated_at->format('d/m/Y H:i A') }}<br>
                        <strong>Report ID:</strong> #{{ $report->id }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
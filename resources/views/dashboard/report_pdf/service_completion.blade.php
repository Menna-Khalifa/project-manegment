<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Service Completion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            padding: 20px;
        }

        .container {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table-header {
            background-color: #b8d4e8;
            font-weight: bold;
            text-align: center !important;
            padding: 10px;
        }

        .info-table td {
            width: 25%;
        }

        .info-table .label-cell {
            font-weight: bold;
            background-color: #e8f4f8;
        }

        .units-table th {
            background-color: #b8d4e8;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
            padding: 8px 4px;
        }

        .units-table td {
            text-align: center;
            font-size: 11px;
        }

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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('dashboard/assets/img/brand/desktop-logo.png') }}" alt="Logo" class="logo">
        </div>

        <h1 class="title">Service Completion</h1>

        <table class="info-table">
            <tr>
                <td colspan="4" class="table-header">AMERICANA</td>
            </tr>
            <tr>
                <td class="label-cell">Date</td>
                <td>{{ $project_amer->date }}</td>
                <td class="label-cell">PO number</td>
                <td>{{ $project_amer->po_num }}</td>
            </tr>
            <tr>
                <td class="label-cell">Region</td>
                <td>{{ $project_amer->region }}</td>
                <td class="label-cell">Brand Store</td>
                <td>{{ $project_amer->store->brand->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">City</td>
                <td>{{ $project_amer->store->city ?? 'N/A' }}</td>
                <td class="label-cell">Store Name</td>
                <td>{{ $project_amer->store->name ?? 'N/A' }}</td>
            </tr>
        </table>

        @if ($project_amer->dept == 'maintenance')
            <table class="units-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">No.</th>
                        <th style="width: 25%;">Type</th>
                        <th style="width: 17%;">Model</th>
                        <th style="width: 10%;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project_amer->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->projectType->name ?? 'N/A' }}</td>
                            <td>{{ $item->projectModel->name ?? 'N/A' }}</td>
                            <td>{{ $item->qty ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>1</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="units-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">No.</th>
                        <th style="width: 25%;">Type of unit</th>
                        <th style="width: 17%;">AC Capacity</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Volt</th>
                        <th style="width: 25%;">AC Brand</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project_amer->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->projectType->name ?? 'N/A' }}</td>
                            <td>{{ $item->projectCapacity->name ?? 'N/A' }}</td>
                            <td>{{ $item->qty ?? 0 }}</td>
                            <td>{{ $item->projectVolt->value ?? 'N/A' }}</td>
                            <td>{{ $item->brand->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>1</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

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
    </div>
</body>

</html>

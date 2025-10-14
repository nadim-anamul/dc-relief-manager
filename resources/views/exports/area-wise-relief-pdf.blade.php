<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area-wise Relief Distribution Report</title>
    
    <style>
        body {
            font-family: 'SolaimanLipi', 'Nikosh', 'Noto Sans Bengali', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: ltr;
            unicode-bidi: embed;
            text-rendering: optimizeLegibility;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ea580c;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #ea580c;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary {
            background-color: #fff7ed;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #ea580c;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-item .label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
        }
        
        .summary-item .value {
            font-size: 16px;
            color: #ea580c;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }
        
        th {
            background-color: #ea580c;
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .count {
            text-align: center;
        }
        
        .location {
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .top-areas {
            margin-bottom: 20px;
        }
        
        .top-areas h3 {
            color: #ea580c;
            margin-bottom: 10px;
        }
        
        .top-areas-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .top-area-item {
            background-color: #fff7ed;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ea580c;
        }
        
        .top-area-item .area-name {
            font-weight: bold;
            color: #ea580c;
        }
        
        .top-area-item .area-amount {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Area-wise Relief Distribution Report</h1>
        <p>Generated on: {{ $exportDate->format('F d, Y H:i:s') }}</p>
        <p>DC Relief Manager System</p>
    </div>

    <div class="summary">
        <h3>Distribution Overview</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Relief Amount</div>
                <div class="value">৳{{ number_format($totalAmount, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Applications</div>
                <div class="value">{{ $totalApplications }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Unique Areas</div>
                <div class="value">{{ $uniqueAreas }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Average per Area</div>
                <div class="value">৳{{ $uniqueAreas > 0 ? number_format($totalAmount / $uniqueAreas, 2) : '0.00' }}</div>
            </div>
        </div>
        
        @if($areaWiseData->count() > 0)
        <div class="summary-grid" style="margin-top: 15px;">
            <div class="summary-item">
                <div class="label">Average per Application</div>
                <div class="value">৳{{ $totalApplications > 0 ? number_format($totalAmount / $totalApplications, 2) : '0.00' }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Min Amount</div>
                <div class="value">৳{{ number_format($areaWiseData->min('total_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Max Amount</div>
                <div class="value">৳{{ number_format($areaWiseData->max('total_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Applications per Area</div>
                <div class="value">{{ $uniqueAreas > 0 ? round($totalApplications / $uniqueAreas, 1) : '0' }}</div>
            </div>
        </div>
        @endif
    </div>

    @if($areaWiseData->count() > 0)
    <div class="top-areas">
        <h3>Top 10 Areas by Relief Amount</h3>
        <div class="top-areas-list">
            @foreach($areaWiseData->take(10) as $index => $area)
            <div class="top-area-item">
                <div class="area-name">{{ $index + 1 }}. {{ $area->zilla_name }}, {{ $area->upazila_name }}</div>
                <div class="area-amount">৳{{ number_format($area->total_amount, 2) }} ({{ $area->application_count }} applications)</div>
            </div>
            @endforeach
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Zilla</th>
                <th>Upazila</th>
                <th>Union</th>
                <th>Ward</th>
                <th>Relief Type</th>
                <th>Org Type</th>
                <th>Total Amount</th>
                <th>Applications</th>
                <th>Avg Amount</th>
                <th>Min Amount</th>
                <th>Max Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($areaWiseData as $area)
            <tr>
                <td class="location">{{ $area->zilla_name }}</td>
                <td>{{ $area->upazila_name }}</td>
                <td>{{ $area->union_name }}</td>
                <td>{{ $area->ward_name }}</td>
                <td>{{ $area->relief_type_name }}</td>
                <td>{{ $area->org_type_name ?? 'Not Specified' }}</td>
                <td class="amount">৳{{ number_format($area->total_amount, 2) }}</td>
                <td class="count">{{ $area->application_count }}</td>
                <td class="amount">৳{{ number_format($area->avg_amount, 2) }}</td>
                <td class="amount">৳{{ number_format($area->min_amount, 2) }}</td>
                <td class="amount">৳{{ number_format($area->max_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No relief distribution data found</h3>
        <p>No approved relief applications match the current filter criteria.</p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the DC Relief Manager System</p>
        <p>For any questions or clarifications, please contact the system administrator</p>
    </div>
</body>
</html>

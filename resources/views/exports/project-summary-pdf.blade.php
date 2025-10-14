<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Summary Report</title>
    
    <!-- Fonts for Bengali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap');
        }
        
        body {
            font-family: 'Noto Sans Bengali', 'SolaimanLipi', 'Nikosh', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: ltr;
            unicode-bidi: embed;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #059669;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #059669;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary {
            background-color: #f0fdf4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #059669;
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
            color: #059669;
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
            background-color: #059669;
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-upcoming {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .duration {
            text-align: center;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Project Summary Report</h1>
        <p>Generated on: {{ $exportDate ? $exportDate->format('F d, Y H:i:s') : 'N/A' }}</p>
        <p>DC Relief Manager System</p>
    </div>

    <div class="summary">
        <h3>Project Overview</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Projects</div>
                <div class="value">{{ $totalProjects }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Active Projects</div>
                <div class="value">{{ $activeProjects }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Completed Projects</div>
                <div class="value">{{ $completedProjects }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Budget</div>
                <div class="value">৳{{ number_format($totalBudget, 2) }}</div>
            </div>
        </div>
        
        @if($projects->count() > 0)
        <div class="summary-grid" style="margin-top: 15px;">
            <div class="summary-item">
                <div class="label">Average Budget</div>
                <div class="value">৳{{ number_format($projects->avg('allocated_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Min Budget</div>
                <div class="value">৳{{ number_format($projects->min('allocated_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Max Budget</div>
                <div class="value">৳{{ number_format($projects->max('allocated_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Avg Duration (Days)</div>
                <div class="value">{{ round($projects->avg(function($project) { return $project->economicYear?->duration_in_days ?? 0; }), 1) }}</div>
            </div>
        </div>
        @endif
    </div>

    @if(isset($reliefTypeStats) && $reliefTypeStats->count() > 0)
    <div class="summary" style="margin-bottom: 20px;">
        <h3>Allocation by Relief Type</h3>
        <div class="summary-grid" style="grid-template-columns: repeat(4, 1fr);">
            @foreach($reliefTypeStats as $allocation)
            <div class="summary-item">
                <div class="label">{{ $allocation->reliefType?->display_name ?? 'Unknown Type' }}</div>
                <div class="value">{{ $allocation->formatted_total }}</div>
                <div class="label" style="font-size: 10px;">{{ $allocation->project_count }} {{ $allocation->project_count == 1 ? 'project' : 'projects' }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($projects->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Economic Year</th>
                <th>Relief Type</th>
                <th>Allocated Amount</th>
                <th>Available Amount</th>
                <th>Used Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->economicYear->name }}{{ $project->economicYear->is_current ? ' (Current)' : '' }}</td>
                <td>{{ $project->reliefType?->display_name ?? $project->reliefType?->name ?? 'Not specified' }}</td>
                <td class="amount">{{ $project->formatted_allocated_amount }}</td>
                <td class="amount">{{ $project->formatted_available_amount }}</td>
                <td class="amount">{{ $project->formatted_used_amount }}</td>
                <td>{{ $project->economicYear?->start_date?->format('Y-m-d') ?? 'N/A' }}</td>
                <td>{{ $project->economicYear?->end_date?->format('Y-m-d') ?? 'N/A' }}</td>
                <td class="duration">{{ $project->economicYear?->duration_in_days ?? 0 }} days</td>
                <td>
                    @if($project->is_active)
                        <span class="status-badge status-active">Active</span>
                    @elseif($project->is_completed)
                        <span class="status-badge status-completed">Completed</span>
                    @else
                        <span class="status-badge status-upcoming">Upcoming</span>
                    @endif
                </td>
                <td>{{ Str::limit($project->remarks, 30) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No projects found</h3>
        <p>No projects match the current filter criteria.</p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the DC Relief Manager System</p>
        <p>For any questions or clarifications, please contact the system administrator</p>
    </div>
</body>
</html>

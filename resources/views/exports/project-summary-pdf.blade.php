<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
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
            padding: 8px;
            text-align: left;
            font-size: 10px;
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
        <p>Generated on: {{ $exportDate->format('F d, Y H:i:s') }}</p>
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
                <div class="value">৳{{ number_format($projects->avg('budget'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Min Budget</div>
                <div class="value">৳{{ number_format($projects->min('budget'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Max Budget</div>
                <div class="value">৳{{ number_format($projects->max('budget'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Avg Duration (Days)</div>
                <div class="value">{{ round($projects->avg('duration_in_days'), 1) }}</div>
            </div>
        </div>
        @endif
    </div>

    @if($projects->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Economic Year</th>
                <th>Relief Type</th>
                <th>Budget</th>
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
                <td>{{ $project->economicYear->name }}</td>
                <td>{{ $project->reliefType->name }}</td>
                <td class="amount">৳{{ number_format($project->budget, 2) }}</td>
                <td>{{ $project->start_date->format('Y-m-d') }}</td>
                <td>{{ $project->end_date->format('Y-m-d') }}</td>
                <td class="duration">{{ $project->duration_in_days }} days</td>
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

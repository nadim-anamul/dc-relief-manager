<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relief Applications Report</title>
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
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #2563eb;
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
            color: #2563eb;
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
            background-color: #2563eb;
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
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .amount {
            text-align: right;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Relief Applications Report</h1>
        <p>Generated on: {{ $exportDate->format('F d, Y H:i:s') }}</p>
        <p>DC Relief Manager System</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Applications</div>
                <div class="value">{{ $reliefApplications->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Pending</div>
                <div class="value">{{ $reliefApplications->where('status', 'pending')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Approved</div>
                <div class="value">{{ $reliefApplications->where('status', 'approved')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Rejected</div>
                <div class="value">{{ $reliefApplications->where('status', 'rejected')->count() }}</div>
            </div>
        </div>
        
        @if($reliefApplications->where('status', 'approved')->count() > 0)
        <div class="summary-grid" style="margin-top: 15px;">
            <div class="summary-item">
                <div class="label">Total Approved Amount</div>
                <div class="value">৳{{ number_format($reliefApplications->where('status', 'approved')->sum('approved_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Requested Amount</div>
                <div class="value">৳{{ number_format($reliefApplications->sum('amount_requested'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Average Approved Amount</div>
                <div class="value">৳{{ number_format($reliefApplications->where('status', 'approved')->avg('approved_amount'), 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Approval Rate</div>
                <div class="value">{{ $reliefApplications->count() > 0 ? round(($reliefApplications->where('status', 'approved')->count() / $reliefApplications->count()) * 100, 1) : 0 }}%</div>
            </div>
        </div>
        @endif
    </div>

    @if($reliefApplications->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Organization</th>
                <th>Date</th>
                <th>Location</th>
                <th>Subject</th>
                <th>Relief Type</th>
                <th>Amount Requested</th>
                <th>Amount Approved</th>
                <th>Status</th>
                <th>Project</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reliefApplications as $application)
            <tr>
                <td>{{ $application->id }}</td>
                <td>{{ $application->organization_name }}</td>
                <td>{{ $application->date->format('Y-m-d') }}</td>
                <td>{{ $application->zilla?->name ?? 'Not specified' }}, {{ $application->upazila?->name ?? 'Not specified' }}</td>
                <td>{{ Str::limit($application->subject, 30) }}</td>
                <td>{{ $application->reliefType?->name ?? 'Not specified' }}</td>
                <td class="amount">৳{{ number_format($application->amount_requested, 2) }}</td>
                <td class="amount">{{ $application->approved_amount ? '৳' . number_format($application->approved_amount, 2) : '-' }}</td>
                <td>
                    <span class="status-badge status-{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </td>
                <td>{{ $application->project->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No relief applications found</h3>
        <p>No applications match the current filter criteria.</p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the DC Relief Manager System</p>
        <p>For any questions or clarifications, please contact the system administrator</p>
    </div>
</body>
</html>

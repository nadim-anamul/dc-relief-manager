<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বরাদ্দ সারসংক্ষেপ রিপোর্ট</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            color: #333;
            background: white;
        }
        
        h1, h2, h3, h4, h5, h6, strong, b, th {
            font-weight: 600;
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
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        th {
            background-color: #059669;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-completed {
            background-color: #e5e7eb;
            color: #374151;
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
        
        .text-center {
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
        
        .relief-type-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .relief-type-item {
            flex: 1 1 calc(25% - 10px);
            min-width: 150px;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
        }
        
        .relief-type-item .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .relief-type-item .value {
            font-size: 14px;
            color: #059669;
            font-weight: bold;
        }
        
        .relief-type-item .count {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <?php
        app()->setLocale('bn');
    ?>
    <div class="header">
        <h1>বরাদ্দ সারসংক্ষেপ রিপোর্ট</h1>
        <p>তৈরির তারিখ: {{ bn_date($exportDate, 'd M Y') }} {{ bn_number($exportDate->format('h:i')) }} {{ $exportDate->format('A') === 'AM' ? 'সকাল' : 'বিকাল' }}</p>
        <p>ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম</p>
    </div>

    <div class="summary">
        <h3>প্রকল্প সারসংক্ষেপ</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">মোট বরাদ্দ</div>
                <div class="value">{{ bn_number($totalProjects) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">সক্রিয় বরাদ্দ</div>
                <div class="value">{{ bn_number($activeProjects) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">সম্পন্ন বরাদ্দ</div>
                <div class="value">{{ bn_number($completedProjects) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">মোট বাজেট</div>
                <div class="value">৳{{ bn_number(number_format($totalBudget, 2)) }}</div>
            </div>
        </div>
    </div>

    @if(isset($reliefTypeStats) && $reliefTypeStats->count() > 0)
    <div class="summary" style="margin-bottom: 20px;">
        <h3>ত্রান কর্মসূচিসমূহ</h3>
        <div class="relief-type-grid">
            @foreach($reliefTypeStats as $allocation)
            <div class="relief-type-item">
                <div class="label">{{ localized_attr($allocation->reliefType, 'name') ?? 'অজানা ধরন' }}</div>
                <div class="value">{{ bn_number($allocation->formatted_total) }}</div>
                <div class="count">{{ bn_number($allocation->project_count) }} টি {{ $allocation->project_count == 1 ? 'বরাদ্দ' : 'বরাদ্দ' }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($projects->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">ক্রমিক</th>
                <th style="width: 18%;">প্রকল্পের নাম</th>
                <th style="width: 10%;">অর্থবছর</th>
                <th style="width: 12%;">ত্রাণের ধরন</th>
                <th style="width: 11%;">বরাদ্দকৃত</th>
                <th style="width: 11%;">অবশিষ্ট</th>
                <th style="width: 11%;">ব্যবহৃত</th>
                <th style="width: 8%;">অবস্থা</th>
                <th style="width: 15%;">মন্তব্য</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $index => $project)
            <tr>
                <td class="text-center">{{ bn_number($index + 1) }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ localized_attr($project->economicYear, 'name') }}</td>
                <td>{{ localized_attr($project->reliefType, 'name') ?? 'উল্লেখ নেই' }}</td>
                <td class="amount">{{ bn_number($project->formatted_allocated_amount) }}</td>
                <td class="amount">{{ bn_number($project->formatted_available_amount) }}</td>
                <td class="amount">{{ bn_number($project->formatted_used_amount) }}</td>
                <td class="text-center">
                    @if($project->status === 'Active')
                        <span class="status-badge status-active">সক্রিয়</span>
                    @elseif($project->status === 'Completed')
                        <span class="status-badge status-completed">সম্পন্ন</span>
                    @else
                        <span class="status-badge status-upcoming">আসন্ন</span>
                    @endif
                </td>
                <td style="font-size: 9px;">{{ $project->remarks ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>কোনো বরাদ্দ পাওয়া যায়নি</h3>
        <p>বর্তমান ফিল্টার অনুযায়ী কোনো বরাদ্দ খুঁজে পাওয়া যায়নি।</p>
    </div>
    @endif

    <div class="footer">
        <p>এই রিপোর্টটি ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম দ্বারা স্বয়ংক্রিয়ভাবে তৈরি করা হয়েছে</p>
        <p>যেকোনো প্রশ্ন বা স্পষ্টীকরণের জন্য, অনুগ্রহ করে সিস্টেম প্রশাসকের সাথে যোগাযোগ করুন</p>
    </div>
</body>
</html>

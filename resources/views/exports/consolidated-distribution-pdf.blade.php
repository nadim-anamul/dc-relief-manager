<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সমন্বিত বিতরণ বিশ্লেষণ রিপোর্ট</title>
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
            font-weight: 700;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .summary {
            background-color: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
        }

        .summary h3 {
            margin: 0 0 15px 0;
            color: #059669;
            font-size: 16px;
            font-weight: 600;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .summary-item {
            text-align: center;
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #dcfce7;
        }

        .summary-item .label {
            font-weight: 600;
            color: #666;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .summary-item .value {
            font-size: 14px;
            color: #059669;
            font-weight: 700;
        }

        .project-budget {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .project-budget h3 {
            margin: 0 0 15px 0;
            color: #059669;
            font-size: 16px;
            font-weight: 600;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .project-item {
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .project-item .name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .project-item .amounts {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .project-item .allocated {
            color: #059669;
        }

        .project-item .distributed {
            color: #dc2626;
        }

        .project-item .available {
            color: #2563eb;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            margin: 5px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #059669;
            transition: width 0.3s ease;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
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
            font-weight: 600;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .amount {
            text-align: right;
            font-weight: 600;
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
            padding-top: 15px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .no-data h3 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>সমন্বিত বিতরণ বিশ্লেষণ রিপোর্ট</h1>
        <p>তৈরির তারিখ: {{ bn_date($exportDate, 'd M Y') }} {{ bn_number($exportDate->format('h:i')) }} {{ $exportDate->format('A') === 'AM' ? 'সকাল' : 'বিকাল' }}</p>
        <p>ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম</p>
    </div>

    <div class="summary">
        <h3>সারসংক্ষেপ</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">মোট আবেদন</div>
                <div class="value">{{ bn_number($data['distribution']->count()) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">মোট পরিমাণ</div>
                <div class="value">
                    @if(isset($data['totalsByUnit']) && count($data['totalsByUnit']) > 0)
                        @foreach($data['totalsByUnit'] as $unit => $total)
                            @php
                                $isMoney = in_array($unit, ['টাকা', 'Taka']) || empty($unit);
                            @endphp
                            @if($isMoney)
                                ৳{{ bn_number(number_format($total, 2)) }}
                            @else
                                {{ bn_number(number_format($total, 2)) }} {{ $unit }}
                            @endif
                            @if(!$loop->last)<br>@endif
                        @endforeach
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="summary-item">
                <div class="label">অনন্য প্রকল্প</div>
                <div class="value">{{ bn_number($data['distribution']->pluck('project_id')->unique()->count()) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">অনন্য সংস্থা</div>
                <div class="value">{{ bn_number($data['distribution']->pluck('organization_name')->unique()->count()) }}</div>
            </div>
        </div>
    </div>

    @if($projectBudgetBreakdown->count() > 0)
    <div class="project-budget">
        <h3>প্রকল্প বাজেট বিশ্লেষণ</h3>
        <div class="project-grid">
            @foreach($projectBudgetBreakdown as $budget)
            <div class="project-item">
                <div class="name">{{ $budget['project']->name }}</div>
                <div class="amounts">
                    @php
                        $pu = $projectUnits[$budget['project']->id] ?? null;
                        $isMoney = $pu['is_money'] ?? false;
                        $unit = $pu['unit'] ?? '';
                    @endphp
                    <span class="allocated">
                        বরাদ্দ:
                        @if($isMoney)
                            ৳{{ bn_number(number_format($budget['allocated_amount'], 2)) }}
                        @else
                            {{ bn_number(number_format($budget['allocated_amount'], 2)) }} {{ $unit }}
                        @endif
                    </span>
                    <span class="distributed">
                        বিতরণ:
                        @if($isMoney)
                            ৳{{ bn_number(number_format($budget['distributed_amount'], 2)) }}
                        @else
                            {{ bn_number(number_format($budget['distributed_amount'], 2)) }} {{ $unit }}
                        @endif
                    </span>
                    <span class="available">
                        অবশিষ্ট:
                        @if($isMoney)
                            ৳{{ bn_number(number_format($budget['available_amount'], 2)) }}
                        @else
                            {{ bn_number(number_format($budget['available_amount'], 2)) }} {{ $unit }}
                        @endif
                    </span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $budget['utilization_percentage'] }}%"></div>
                </div>
                <div style="text-align: center; font-size: 9px; color: #666;">
                    {{ bn_number(number_format($budget['utilization_percentage'], 1)) }}% ব্যবহার
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($data['distribution']->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">ক্রমিক</th>
                <th style="width: 18%;">সংস্থার নাম</th>
                <th style="width: 8%;">তারিখ</th>
                <th style="width: 10%;">জেলা</th>
                <th style="width: 10%;">উপজেলা</th>
                <th style="width: 10%;">ইউনিয়ন</th>
                <th style="width: 15%;">প্রকল্পের নাম</th>
                <th style="width: 10%;">ত্রাণের ধরন</th>
                <th style="width: 8%;">আবেদনকৃত</th>
                <th style="width: 8%;">অনুমোদিত</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['distribution'] as $index => $application)
            <tr>
                <td class="text-center">{{ bn_number($index + 1) }}</td>
                <td>{{ $application->organization_name }}</td>
                <td class="text-center">{{ bn_date($application->date, 'd/m/Y') }}</td>
                <td>{{ localized_attr($application->zilla, 'name') ?? 'উল্লেখ নেই' }}</td>
                <td>{{ localized_attr($application->upazila, 'name') ?? 'উল্লেখ নেই' }}</td>
                <td>{{ localized_attr($application->union, 'name') ?? 'উল্লেখ নেই' }}</td>
                <td>{{ $application->project->name ?? 'উল্লেখ নেই' }}</td>
                <td>{{ localized_attr($application->reliefType, 'name') ?? 'উল্লেখ নেই' }}</td>
                @php
                    $apu = $projectUnits[$application->project_id] ?? null;
                    $aIsMoney = $apu['is_money'] ?? false;
                    $aUnit = $apu['unit'] ?? '';
                @endphp
                <td class="amount">
                    @if($aIsMoney)
                        ৳{{ bn_number(number_format($application->amount_requested, 2)) }}
                    @else
                        {{ bn_number(number_format($application->amount_requested, 2)) }} {{ $aUnit }}
                    @endif
                </td>
                <td class="amount">
                    @if($aIsMoney)
                        ৳{{ bn_number(number_format($application->approved_amount, 2)) }}
                    @else
                        {{ bn_number(number_format($application->approved_amount, 2)) }} {{ $aUnit }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h3>কোনো বিতরণ তথ্য পাওয়া যায়নি</h3>
        <p>বর্তমান ফিল্টার অনুযায়ী কোনো বিতরণ তথ্য খুঁজে পাওয়া যায়নি।</p>
    </div>
    @endif

    <div class="footer">
        <p>এই রিপোর্টটি ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম দ্বারা স্বয়ংক্রিয়ভাবে তৈরি করা হয়েছে</p>
        <p>যেকোনো প্রশ্ন বা স্পষ্টীকরণের জন্য, অনুগ্রহ করে সিস্টেম প্রশাসকের সাথে যোগাযোগ করুন</p>
    </div>
</body>
</html>

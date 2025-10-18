<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বিস্তারিত ইউনিয়ন বিতরণ রিপোর্ট</title>
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
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
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
        <h1>বিস্তারিত ইউনিয়ন বিতরণ রিপোর্ট</h1>
        <p>তৈরির তারিখ: {{ bn_date($exportDate, 'd M Y') }} {{ bn_number($exportDate->format('h:i')) }} {{ $exportDate->format('A') === 'AM' ? 'সকাল' : 'বিকাল' }}</p>
        <p>ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম</p>
    </div>

    <div class="summary">
        <h3>সারসংক্ষেপ</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">মোট রেকর্ড</div>
                <div class="value">{{ bn_number($data['pagination']['total_items']) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">মোট পরিমাণ</div>
                <div class="value">৳{{ bn_number(number_format($data['data']->sum('total_amount'), 2)) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">মোট আবেদন</div>
                <div class="value">{{ bn_number($data['data']->sum('application_count')) }}</div>
            </div>
        </div>
    </div>

    @if($data['data']->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">ক্রমিক</th>
                <th style="width: 20%;">প্রকল্পের নাম</th>
                <th style="width: 12%;">জেলা</th>
                <th style="width: 12%;">উপজেলা</th>
                <th style="width: 12%;">ইউনিয়ন</th>
                <th style="width: 12%;">ত্রাণের ধরন</th>
                <th style="width: 14%;">মোট পরিমাণ</th>
                <th style="width: 14%;">আবেদনের সংখ্যা</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['data'] as $index => $item)
            <tr>
                <td class="text-center">{{ bn_number($index + 1) }}</td>
                <td>{{ $item->project_name }}</td>
                <td>{{ $item->zilla_name }}</td>
                <td>{{ $item->upazila_name }}</td>
                <td>{{ $item->union_name_bn ?: $item->union_name }}</td>
                <td>{{ $item->relief_type_name }}</td>
                <td class="amount">৳{{ bn_number(number_format($item->total_amount, 2)) }}</td>
                <td class="text-center">{{ bn_number($item->application_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h3>কোনো ইউনিয়ন বিতরণ তথ্য পাওয়া যায়নি</h3>
        <p>বর্তমান ফিল্টার অনুযায়ী কোনো ইউনিয়ন বিতরণ তথ্য খুঁজে পাওয়া যায়নি।</p>
    </div>
    @endif

    <div class="footer">
        <p>এই রিপোর্টটি ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম দ্বারা স্বয়ংক্রিয়ভাবে তৈরি করা হয়েছে</p>
        <p>যেকোনো প্রশ্ন বা স্পষ্টীকরণের জন্য, অনুগ্রহ করে সিস্টেম প্রশাসকের সাথে যোগাযোগ করুন</p>
    </div>
</body>
</html>

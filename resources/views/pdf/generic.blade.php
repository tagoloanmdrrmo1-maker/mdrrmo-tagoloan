<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_type_display ?? 'Report' }}</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 {{ isset($orientation) ? $orientation : 'portrait' }};
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #242F41;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #242F41;
            font-size: 22px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header h2 {
            color: #666;
            font-size: 13px;
            margin: 0;
            font-weight: normal;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .info-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #242F41;
        }
        .info-value {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #242F41;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_type_display ?? 'System Report' }}</h1>
        <h2>Generated on {{ $generated_at ?? now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    @if(isset($requestor_name) || isset($organization) || isset($date_range))
        <div class="info-section">
            @if(isset($requestor_name))
                <div class="info-item">
                    <span class="info-label">Requested by:</span>
                    <span class="info-value">{{ $requestor_name }}</span>
                </div>
            @endif
            @if(isset($organization))
                <div class="info-item">
                    <span class="info-label">Organization:</span>
                    <span class="info-value">{{ $organization }}</span>
                </div>
            @endif
            @if(isset($date_range))
                <div class="info-item">
                    <span class="info-label">Date Range:</span>
                    <span class="info-value">{{ $date_range }}</span>
                </div>
            @endif
        </div>
    @endif

    @if(isset($data) && count($data) > 0)
        <table>
            <thead>
                <tr>
                    @php
                        $firstItem = $data->first() ?? $data[0] ?? null;
                        $headers = $firstItem ? array_keys((array)$firstItem) : [];
                    @endphp
                    @foreach($headers as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($headers as $header)
                            <td>{{ isset($row[$header]) ? $row[$header] : (isset($row->$header) ? $row->$header : 'N/A') }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No data available for this report.</p>
        </div>
    @endif

    <div class="footer">
        <p>MDDRMO Rainfall Monitoring Dashboard | {{ $report_type_display ?? 'Report' }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0a1d3a;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0a1d3a;
            font-size: 20px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header h2 {
            color: #666;
            font-size: 12px;
            margin: 0;
            font-weight: normal;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            font-size: 9px;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            color: #0a1d3a;
        }
        .summary {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #90caf9;
            text-align: center;
        }
        .summary-item {
            display: inline-block;
            margin-right: 25px;
        }
        .summary-label {
            font-weight: bold;
            color: #0a1d3a;
        }
        .summary-value {
            color: #1976d2;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #0a1d3a;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .device-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .rainfall-value {
            font-weight: bold;
        }
        .intensity {
            padding: 2px 4px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .intensity-torrential {
            background-color: #8e24aa;
            color: white;
        }
        .intensity-intense {
            background-color: #d32f2f;
            color: white;
        }
        .intensity-heavy {
            background-color: #f57c00;
            color: white;
        }
        .intensity-moderate {
            background-color: #fbc02d;
            color: black;
        }
        .intensity-light {
            background-color: #4caf50;
            color: white;
        }
        .intensity-no-rain {
            background-color: #e0e0e0;
            color: #666;
        }
        .footer {
            position: fixed;
            bottom: 0.5cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    @if(isset($filters) && !empty($filters))
        <div class="filters">
            @foreach($filters as $label => $value)
                @if($value)
                    <div class="filter-item">
                        <span class="filter-label">{{ $label }}:</span>
                        <span>{{ $value }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @if(isset($summary) && !empty($summary))
        <div class="summary">
            @foreach($summary as $label => $value)
                <div class="summary-item">
                    <span class="summary-label">{{ $label }}:</span>
                    <span class="summary-value">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @if($type === 'rainfall')
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Device ID</th>
                    <th style="width: 15%;">Location</th>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 12%;">Time</th>
                    <th style="width: 10%;">Tips Count</th>
                    <th style="width: 12%;">Rainfall (mm)</th>
                    <th style="width: 12%;">Intensity Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $record)
                    <tr>
                        <td class="device-id">{{ $record->dev_id ?? 'N/A' }}</td>
                        <td>{{ $record->dev_location ?? 'N/A' }}</td>
                        <td>{{ sprintf('%02d/%02d/%04d', $record->month, $record->day, $record->year) }}</td>
                        <td>
                            @if(isset($record->created_at) && $record->created_at)
                                {{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }}
                            @else
                                --:--
                            @endif
                        </td>
                        <td>{{ $record->tip_count ?? 0 }}</td>
                        <td class="rainfall-value">{{ number_format($record->cumulative_rainfall ?? 0, 2) }}</td>
                        <td>
                            @php
                                $intensityClass = 'intensity-' . strtolower(str_replace(' ', '-', $record->intensity_level ?? 'no-rain'));
                            @endphp
                            <span class="intensity {{ $intensityClass }}">
                                {{ $record->intensity_level ?? 'No Rain' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="no-data">No rainfall data found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($type === 'average_daily')
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Device ID</th>
                    <th style="width: 18%;">Location</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 12%;">Avg Rainfall (mm)</th>
                    <th style="width: 11%;">Total Tips</th>
                    <th style="width: 12%;">Total Rainfall (mm)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $record)
                    <tr>
                        <td class="device-id">{{ $record->dev_id ?? 'N/A' }}</td>
                        <td>{{ $record->dev_location ?? 'N/A' }}</td>
                        <td>{{ $record->date ?? 'N/A' }}</td>
                        <td class="rainfall-value">{{ number_format($record->average_rainfall ?? 0, 2) }}</td>
                        <td>{{ number_format($record->total_tips ?? 0, 0) }}</td>
                        <td class="rainfall-value">{{ number_format($record->total_rainfall ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="no-data">No daily average data found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($type === 'average_monthly')
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Device ID</th>
                    <th style="width: 20%;">Location</th>
                    <th style="width: 10%;">Month</th>
                    <th style="width: 10%;">Year</th>
                    <th style="width: 15%;">Avg Rainfall (mm)</th>
                    <th style="width: 15%;">Total Tips</th>
                    <th style="width: 15%;">Total Rainfall (mm)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $record)
                    <tr>
                        <td class="device-id">{{ $record->dev_id ?? 'N/A' }}</td>
                        <td>{{ $record->dev_location ?? 'N/A' }}</td>
                        <td>{{ date('F', mktime(0, 0, 0, $record->month ?? 1, 1)) }}</td>
                        <td>{{ $record->year ?? 'N/A' }}</td>
                        <td class="rainfall-value">{{ number_format($record->average_rainfall ?? 0, 2) }}</td>
                        <td>{{ number_format($record->total_tips ?? 0, 0) }}</td>
                        <td class="rainfall-value">{{ number_format($record->total_rainfall ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">No monthly average data found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Rainfall Dashboard System | {{ $title }} | Page <span class="pagenum"></span> of <span class="pagecount"></span></p>
    </div>
</body>
</html>
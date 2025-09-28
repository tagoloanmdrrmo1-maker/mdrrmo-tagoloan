<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devices Report</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
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
            border-bottom: 2px solid #0a1d3a;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0a1d3a;
            font-size: 24px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header h2 {
            color: #666;
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            color: #0a1d3a;
        }
        .summary-value {
            color: #28a745;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
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
        tbody tr:hover {
            background-color: #e3f2fd;
        }
        .device-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-maintenance {
            background-color: #cce5f4;
            color: #004085;
        }
        .status-offline {
            background-color: #f1f3f4;
            color: #5f6368;
        }
        .intensity-light { color: #28a745; }
        .intensity-moderate { color: #007bff; }
        .intensity-heavy { color: #ffc107; }
        .intensity-intense { color: #fd7e14; }
        .intensity-torrential { color: #dc3545; }
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Device Management Report</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Devices:</span>
            <span class="summary-value">{{ $devices->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Active Devices:</span>
            <span class="summary-value">{{ $devices->where('status', 'active')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Pending Devices:</span>
            <span class="summary-value">{{ $devices->where('status', 'pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Unique Locations:</span>
            <span class="summary-value">{{ $devices->pluck('dev_location')->unique()->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Device ID</th>
                <th style="width: 12%;">Serial Number</th>
                <th style="width: 18%;">Location</th>
                <th style="width: 12%;">Current Rainfall (mm)</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 12%;">Intensity Level</th>
                <th style="width: 12%;">Date Installed</th>
                <th style="width: 16%;">Last Reading</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $device)
                <tr>
                    <td class="device-id">DEV{{ str_pad($device->dev_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $device->serial_number }}</td>
                    <td>{{ $device->dev_location }}</td>
                    <td>{{ number_format($device->cumulative_rainfall, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($device->status) {
                                'active' => 'status-active',
                                'pending' => 'status-pending',
                                'inactive' => 'status-inactive',
                                'maintenance' => 'status-maintenance',
                                'offline' => 'status-offline',
                                default => 'status-inactive'
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($device->status) }}</span>
                    </td>
                    <td>
                        @if($device->latest_intensity)
                            @php
                                $intensityClass = match(strtolower($device->latest_intensity)) {
                                    'light' => 'intensity-light',
                                    'moderate' => 'intensity-moderate',
                                    'heavy' => 'intensity-heavy',
                                    'intense' => 'intensity-intense',
                                    'torrential' => 'intensity-torrential',
                                    default => ''
                                };
                            @endphp
                            <span class="{{ $intensityClass }}">{{ ucfirst($device->latest_intensity) }}</span>
                        @else
                            <span style="color: #999;">No data</span>
                        @endif
                    </td>
                    <td>{{ $device->date_installed ? \Carbon\Carbon::parse($device->date_installed)->format('M j, Y') : 'N/A' }}</td>
                    <td>
                        @if($device->last_reading)
                            {{ \Carbon\Carbon::parse($device->last_reading)->format('M j, Y H:i') }}
                        @else
                            <span style="color: #999;">No readings</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">No devices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Rainfall Monitoring System | Page <span class="pagenum"></span></p>
    </div>
</body>
</html>
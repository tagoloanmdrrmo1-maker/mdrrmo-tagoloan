<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Messages Report</title>
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
        tbody tr:hover {
            background-color: #e3f2fd;
        }
        .message-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-sent {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .intensity-light { color: #28a745; }
        .intensity-moderate { color: #007bff; }
        .intensity-heavy { color: #ffc107; }
        .intensity-intense { color: #fd7e14; }
        .intensity-torrential { color: #dc3545; }
        .text-description {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 8px;
        }
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
        <h1>Message Management Report</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Messages:</span>
            <span class="summary-value">{{ $messages->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Sent Messages:</span>
            <span class="summary-value">{{ $messages->where('status', 'Sent')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Pending Messages:</span>
            <span class="summary-value">{{ $messages->where('status', 'Pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Failed Messages:</span>
            <span class="summary-value">{{ $messages->where('status', 'Failed')->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Message ID</th>
                <th style="width: 12%;">Intensity Level</th>
                <th style="width: 15%;">Contact Name</th>
                <th style="width: 12%;">Brgy Location</th>
                <th style="width: 12%;">Contact Number</th>
                <th style="width: 25%;">Text Description</th>
                <th style="width: 12%;">Date Created</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $message)
                <tr>
                    <td class="message-id">MSG{{ str_pad($message->mes_id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        @if($message->intensity_level)
                            @php
                                $intensityClass = match(strtolower($message->intensity_level)) {
                                    'light' => 'intensity-light',
                                    'moderate' => 'intensity-moderate',
                                    'heavy' => 'intensity-heavy',
                                    'intense' => 'intensity-intense',
                                    'torrential' => 'intensity-torrential',
                                    default => ''
                                };
                            @endphp
                            <span class="{{ $intensityClass }}">{{ ucfirst($message->intensity_level) }}</span>
                        @else
                            <span style="color: #999;">N/A</span>
                        @endif
                    </td>
                    <td>{{ $message->contact ? ($message->contact->firstname . ' ' . ($message->contact->middlename ?? '') . ' ' . $message->contact->lastname) : 'Unknown Contact' }}</td>
                    <td>{{ $message->brgy_location }}</td>
                    <td>{{ $message->contact_num ? '0' . $message->contact_num : 'N/A' }}</td>
                    <td class="text-description" title="{{ $message->text_desc }}">
                        {{ strlen($message->text_desc) > 80 ? substr($message->text_desc, 0, 80) . '...' : $message->text_desc }}
                    </td>
                    <td>{{ $message->date_created ? \Carbon\Carbon::parse($message->date_created)->format('M j, Y H:i') : 'N/A' }}</td>
                    <td>
                        @php
                            $statusClass = match(strtolower($message->status ?? 'pending')) {
                                'sent' => 'status-sent',
                                'pending' => 'status-pending',
                                'failed' => 'status-failed',
                                default => 'status-pending'
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($message->status ?? 'Pending') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">No messages found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Rainfall Monitoring System | Page <span class="pagenum"></span></p>
    </div>
</body>
</html>
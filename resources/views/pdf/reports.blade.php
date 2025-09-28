<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reports Summary</title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
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
            text-align: left;
            vertical-align: top;
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
        .report-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-approved {
            background-color: #17a2b8;
            color: white;
        }
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        .status-rejected {
            background-color: #dc3545;
            color: white;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Reports Management Summary</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Reports:</span>
            <span class="summary-value">{{ $reports->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Pending:</span>
            <span class="summary-value">{{ $reports->where('status', 'pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Approved:</span>
            <span class="summary-value">{{ $reports->where('status', 'approved')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Completed:</span>
            <span class="summary-value">{{ $reports->where('status', 'completed')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Rejected:</span>
            <span class="summary-value">{{ $reports->where('status', 'rejected')->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Report ID</th>
                <th style="width: 15%;">Report Type</th>
                <th style="width: 15%;">Requestor</th>
                <th style="width: 15%;">Organization</th>
                <th style="width: 12%;">Start Date</th>
                <th style="width: 12%;">End Date</th>
                <th style="width: 13%;">Purpose</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td class="report-id">R{{ str_pad($report->report_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $report->report_type ?? 'N/A' }}</td>
                    <td>
                        @if($report->requestor)
                            {{ trim(($report->requestor->first_name ?? '') . ' ' . ($report->requestor->last_name ?? '')) }}
                        @elseif($report->requestor_type === 'no_requestor')
                            <em>Internal Request</em>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $report->organization ?? 'N/A' }}</td>
                    <td>{{ $report->start_date ? \Carbon\Carbon::parse($report->start_date)->format('M j, Y') : 'N/A' }}</td>
                    <td>{{ $report->end_date ? \Carbon\Carbon::parse($report->end_date)->format('M j, Y') : 'N/A' }}</td>
                    <td style="font-size: 9px;">{{ Str::limit($report->purpose ?? '', 40) }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge status-{{ $report->status }}">
                            {{ strtoupper($report->status ?? 'PENDING') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #666; font-style: italic;">No reports found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rainfall Dashboard System | Page <span class="pagenum"></span> of <span class="pagecount"></span></p>
    </div>
</body>
</html>
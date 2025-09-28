<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contacts Report</title>
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
            font-size: 11px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 6px;
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
        tbody tr:hover {
            background-color: #e3f2fd;
        }
        .contact-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .contact-name {
            font-weight: bold;
        }
        .position-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .contact-numbers {
            font-size: 10px;
            line-height: 1.3;
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
        <h1>Contacts Information Report</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Contacts:</span>
            <span class="summary-value">{{ $contacts->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Active Contacts:</span>
            <span class="summary-value">{{ $contacts->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Unique Positions:</span>
            <span class="summary-value">{{ $contacts->pluck('position')->unique()->filter()->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Contact ID</th>
                <th style="width: 25%;">Full Name</th>
                <th style="width: 20%;">Location</th>
                <th style="width: 18%;">Contact Numbers</th>
                <th style="width: 15%;">Position</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                @php
                    $fullName = trim(($contact->firstname ?? '') . ' ' . (($contact->middlename ?? '') ? ($contact->middlename . ' ') : '') . ($contact->lastname ?? ''));
                    $contactNumbers = $contact->contact_numbers ? json_decode($contact->contact_numbers, true) : [];
                @endphp
                <tr>
                    <td class="contact-id">CT{{ str_pad($contact->contact_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="contact-name">{{ $fullName ?: 'N/A' }}</td>
                    <td>{{ $contact->brgy_location ?? 'N/A' }}</td>
                    <td class="contact-numbers">
                        @if(!empty($contactNumbers))
                            @foreach($contactNumbers as $number)
                                <div>0{{ $number }}</div>
                            @endforeach
                        @else
                            <span style="color: #999;">No contact numbers</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->position)
                            <span class="position-badge">{{ $contact->position }}</span>
                        @else
                            <span style="color: #999;">—</span>
                        @endif
                    </td>
                    <td style="text-align: center; color: #28a745; font-weight: bold;">Active</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; font-style: italic;">No contacts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rainfall Dashboard System | Page <span class="pagenum"></span> of <span class="pagecount"></span></p>
    </div>
</body>
</html>
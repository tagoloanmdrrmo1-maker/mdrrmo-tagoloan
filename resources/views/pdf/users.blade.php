<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Report</title>
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
        .user-id {
            font-weight: bold;
            color: #0a1d3a;
        }
        .user-name {
            font-weight: bold;
        }
        .role-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .role-admin {
            background-color: #dc3545;
            color: white;
        }
        .role-user {
            background-color: #28a745;
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
        <h1>Users Management Report</h1>
        <h2>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</h2>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Users:</span>
            <span class="summary-value">{{ $users->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Admin Users:</span>
            <span class="summary-value">{{ $users->where('role', 'admin')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Regular Users:</span>
            <span class="summary-value">{{ $users->where('role', 'user')->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">User ID</th>
                <th style="width: 25%;">Full Name</th>
                <th style="width: 15%;">Username</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 15%;">Contact Number</th>
                <th style="width: 10%;">Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    // Get user information directly from the users table instead of user_profiles
                    $fullName = trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A';
                    $contactNum = $user->contact_num ?? 'N/A';
                @endphp
                <tr>
                    <td class="user-id">U{{ str_pad($user->user_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="user-name">{{ $fullName }}</td>
                    <td>{{ $user->username ?? 'N/A' }}</td>
                    <td>{{ $user->email ?? 'N/A' }}</td>
                    <td>{{ $contactNum }}</td>
                    <td style="text-align: center;">
                        <span class="role-badge role-{{ $user->role }}">
                            {{ strtoupper($user->role ?? 'USER') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; font-style: italic;">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rainfall Dashboard System | Page <span class="pagenum"></span> of <span class="pagecount"></span></p>
    </div>
</body>
</html>
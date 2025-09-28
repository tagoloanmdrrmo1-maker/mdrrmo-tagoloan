@extends('layouts.app')

@section('title', 'Reports Management - MDDRMO Rainfall Monitoring')
@section('page_heading', 'Reports Management')

@section('content')
<style>
/* Enhanced Table Styling */
.min-w-full tbody tr:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

/* Enhanced Modal Backdrop */
.backdrop-blur-sm {
    backdrop-filter: blur(3px);
}

/* Enhanced Button Styling */
.btn-primary {
    background: linear-gradient(135deg, #185ea6 0%, #134d8a 100%);
    box-shadow: 0 2px 4px rgba(24, 94, 166, 0.2);
    transition: all 0.2s ease;
}

.btn-primary:hover {
    box-shadow: 0 4px 8px rgba(24, 94, 166, 0.3);
    transform: translateY(-1px);
}

/* Enhanced Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.05em;
}

/* Enhanced Action Buttons */
.action-btn {
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Enhanced Form Inputs */
.form-input {
    width: 100%;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
    background-color: #ffffff;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-color: #ffffff;
}

.form-select {
    width: 100%;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    background-color: white;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-right: 40px;
}

.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    width: 100%;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
    background-color: #ffffff;
    resize: vertical;
}

.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-color: #ffffff;
}

/* Enhanced Modal */
.modal-container {
    backdrop-filter: blur(3px);
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Search and Filter Styling */
.search-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m19 19-3.5-3.5m0 0a7 7 0 1 1-10-10 7 7 0 0 1 10 10Z'/%3e%3c/svg%3e");
    background-position: left 12px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-left: 40px;
}

.filter-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-right: 40px;
}

.error-message {
    color: #dc2626;
    font-size: 14px;
    font-weight: 500;
    margin-top: 4px;
    display: block;
    min-height: 0;
    transition: all 0.2s ease;
}

.error-message:empty {
    display: none;
}

/* Form field validation styles */
.form-field-valid {
    border-color: #10b981 !important;
    background-color: #f0fdf4 !important;
}

.form-field-invalid {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}

/* Error state for form inputs */
.form-input.error,
.form-select.error,
.form-textarea.error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.form-input.error:focus,
.form-select.error:focus,
.form-textarea.error:focus {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

/* Banner styles are now handled by the unified system in app.blade.php */
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Banner Container -->
    <div id="alert-banner-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[9999999] flex flex-col items-center space-y-2" style="width: 90%; max-width: 600px;"></div>
    
    <!-- Statistics Cards Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Reports</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Requests</p>
                    <p class="text-2xl font-bold text-yellow-600">3</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Completed Reports</p>
                    <p class="text-2xl font-bold text-green-600">9</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between gap-4">
            <!-- Search Section -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-80"
                                placeholder="Search messages..."
                            >
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-4 bg-gray-700 text-white rounded-r-lg">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                </form>
                
                <!-- Status Filter -->
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </form>
            </div>
                    
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button id="viewRequestorsBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors flex items-center gap-2">
                    <i class="fas fa-users"></i> View Requestors
                </button>
                <button id="requestDataBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Generate Report
                </button>
                <button id="exportBtn" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Reports Table Section -->
    <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full mt-6">
        <div class="px-5 py-3 rounded-t-xl bg-[#242F41] text-white">
            <h3 class="text-base font-medium flex items-center gap-2">
                <i class="fas fa-chart-bar w-4"></i>
                Rainfall Reports
            </h3>
        </div>
        
        <div class="overflow-x-hidden">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Report ID</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Report Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Purpose</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Requestor</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Organization</th>                        
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Date Created</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Generated By</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="reportsTableBody">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}" data-report-id="{{ $report->report_id }}">
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">
                                RPT{{ str_pad($report->report_id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $report->report_type)) }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900">
                                {{ Str::limit($report->purpose ?? '-', 50) }}
                            </td>                    
                            <td class="px-5 py-3 text-sm text-gray-900">
                                @php
                                    // Get requestor information
                                    $requestorName = 'N/A';
                                    if ($report->requestor_type === 'no_requestor') {
                                        $requestorName = 'Internal Request';
                                    } elseif ($report->requestor_type === 'old_requestor' && $report->requestor) {
                                        $requestorName = trim(($report->requestor->first_name ?? '') . ' ' . ($report->requestor->last_name ?? ''));
                                    } elseif ($report->requestor_type === 'new_requestor') {
                                        // For new requestors, we might not have created the requestor record yet
                                        // Display organization or a generic name
                                        $requestorName = $report->organization ?? 'New Requestor';
                                    }
                                @endphp
                                {{ $requestorName }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900">
                                {{ $report->organization ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900">
                                {{ $report->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900">
                                @php
                                    // Get user information directly from the users table instead of user_profiles
                                    $fullName = null;
                                    if ($report->user) {
                                        $fullName = trim(($report->user->first_name ?? '') . ' ' . ($report->user->middle_name ?? '') . ' ' . ($report->user->last_name ?? ''));
                                    }
                                @endphp
                                {{ $fullName ?: ($report->user->username ?? 'Unknown') }}
                            </td>
                            <td class="px-5 py-3 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button
                                        onclick="viewReport('{{ $report->report_id }}')"
                                        class="action-btn w-8 h-8 bg-gray-500 text-white rounded-full hover:bg-gray-600 flex items-center justify-center"
                                        title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                        </svg>
                                    </button>
                                    <button
                                        onclick="updateReportStatus('{{ $report->report_id }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full hover:bg-green-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-400 focus-visible:ring-offset-2 shadow-sm transition-colors"
                                        title="Update Status">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noReportsRow">
                            <td colspan="8" class="px-5 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-file-alt text-gray-300 text-4xl mb-2"></i>
                                    <p>No reports found.</p>
                                    <p class="text-sm">Create your first report to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        
<!-- Request Data Modal -->
<div id="requestDataModal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-2 hidden">
    <div class="modal-content bg-white rounded-xl w-full max-w-3xl relative transform transition-all duration-300 scale-100">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-5 py-3 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Generate Report</h2>
                        <p class="text-blue-100 text-sm">Generate a new report with requestor information</p>
                    </div>
                </div>
                <button id="closeModal" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-4" x-data="{
            requestorType: 'no_requestor',
            selectedRequestor: null
        }">
            <form id="requestDataForm" method="POST" action="{{ route('reports.generate') }}" novalidate>
                @csrf
                
                <!-- Requestor Type Selection -->
                <div class="mb-4">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-users text-blue-600"></i>
                            Requestor Type <span class="text-red-500">*</span>
                        </span>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="requestorType === 'no_requestor' ? 'border-blue-500 bg-blue-50' : ''">
                                <input type="radio" name="requestor_type" value="no_requestor" x-model="requestorType" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center" :class="requestorType === 'no_requestor' ? 'border-blue-500 bg-blue-500' : ''">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="requestorType === 'no_requestor'"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">No Requestor</div>
                                        <div class="text-sm text-gray-500">Internal report</div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="requestorType === 'old_requestor' ? 'border-blue-500 bg-blue-50' : ''">
                                <input type="radio" name="requestor_type" value="old_requestor" x-model="requestorType" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center" :class="requestorType === 'old_requestor' ? 'border-blue-500 bg-blue-500' : ''">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="requestorType === 'old_requestor'"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Old Requestor</div>
                                        <div class="text-sm text-gray-500">Select existing</div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="requestorType === 'new_requestor' ? 'border-blue-500 bg-blue-50' : ''">
                                <input type="radio" name="requestor_type" value="new_requestor" x-model="requestorType" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center" :class="requestorType === 'new_requestor' ? 'border-blue-500 bg-blue-500' : ''">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="requestorType === 'new_requestor'"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">New Requestor</div>
                                        <div class="text-sm text-gray-500">Create new</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div id="requestor_type_error" class="error-message" style="display: none;"></div>
                    </label>
                </div>

                <!-- Old Requestor Selection -->
                <div x-show="requestorType === 'old_requestor'" x-transition class="mb-4">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-user-check text-blue-600"></i>
                            Select Existing Requestor <span class="text-red-500">*</span>
                        </span>
                        <select name="requestor_id" x-model="selectedRequestor" class="form-select">
                            <option value="">— Select requestor —</option>
                            @foreach($requestors as $requestor)
                                <option value="{{ $requestor->requestor_id }}" data-org="{{ $requestor->organization }}">
                                    {{ $requestor->full_name }} ({{ $requestor->organization }})
                                </option>
                            @endforeach
                        </select>
                        <div id="requestor_id_error" class="error-message" style="display: none;"></div>
                        <div id="requestor_id_success" class="success-message mt-1" style="display: none;"></div>
                    </label>
                </div>

                <!-- New Requestor Fields -->
                <div x-show="requestorType === 'new_requestor'" x-transition class="mb-4">
                    <div class="border border-gray-200 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-user-plus text-blue-600"></i>
                            New Requestor Information
                        </h4>
                        
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                            <div>
                                <label class="block">
                                    <span class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></span>
                                    <input name="first_name" type="text" class="form-input" placeholder="Enter first name">
                                    <div id="first_name_error" class="error-message" style="display: none;"></div>
                                    <div id="first_name_success" class="success-message mt-1" style="display: none;"></div>
                                </label>
                            </div>
                            <div>
                                <label class="block">
                                    <span class="block text-sm font-medium text-gray-700 mb-1">Middle Name</span>
                                    <input name="middle_name" type="text" class="form-input" placeholder="Enter middle name (optional)">
                                    <div id="middle_name_error" class="error-message" style="display: none;"></div>
                                    <div id="middle_name_success" class="success-message mt-1" style="display: none;"></div>
                                </label>
                            </div>
                            <div>
                                <label class="block">
                                    <span class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></span>
                                    <input name="last_name" type="text" class="form-input" placeholder="Enter last name">
                                    <div id="last_name_error" class="error-message" style="display: none;"></div>
                                    <div id="last_name_success" class="success-message mt-1" style="display: none;"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Organization Field -->
                        <div>
                            <label class="block">
                                <span class="block text-sm font-medium text-gray-700 mb-1">Organization Name <span class="text-red-500">*</span></span>
                                <input name="organization" type="text" class="form-input" placeholder="Enter organization name">
                                <div id="organization_error" class="error-message" style="display: none;"></div>
                                <div id="organization_success" class="success-message mt-1" style="display: none;"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Report Type Field -->
                <div class="mb-4">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                            Select Report Type <span class="text-red-500">*</span>
                        </span>
                        <select name="report_type" class="form-select" required>
                            <option value="">— Select reports type —</option>
                            <option value="rainfall_history">Rainfall History Report</option>
                            <option value="message_history">Message Management Report</option>
                            <option value="device_info">Device Management Report</option>
                            <option value="contacts_info">Contacts Information Report</option>
                            <option value="user_management">User Management Report</option>
                            <option value="reports_summary">Reports Summary Report</option>
                        </select>
                        <div id="report_type_error" class="error-message" style="display: none;"></div>
                        <div id="report_type_success" class="success-message mt-1" style="display: none;"></div>
                    </label>
                </div>

                <!-- Date Range Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block">
                            <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar text-blue-600"></i>
                                Start Date
                            </span>
                            <input name="start_date" type="date" class="form-input">
                            <div id="start_date_error" class="error-message" style="display: none;"></div>
                            <div id="start_date_success" class="success-message mt-1" style="display: none;"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block">
                            <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar text-blue-600"></i>
                                End Date
                            </span>
                            <input name="end_date" type="date" class="form-input">
                            <div id="end_date_error" class="error-message" style="display: none;"></div>
                            <div id="end_date_success" class="success-message mt-1" style="display: none;"></div>
                        </label>
                    </div>
                </div>

                <!-- Purpose Field - Hidden when no requestor is selected -->
                <div x-show="requestorType !== 'no_requestor'" x-transition class="mb-4">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-blue-600"></i>
                            Purpose <span class="text-red-500">*</span>
                        </span>
                        <textarea name="purpose" rows="3" class="form-textarea" placeholder="Describe the purpose of this report (minimum 10 characters)" maxlength="500"></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div id="purpose_error" class="error-message" style="display: none;"></div>
                            <div id="purpose_success" class="success-message mt-1" style="display: none;"></div>
                            <div class="text-xs text-gray-500">
                                <span id="purpose_count">0</span>/500 characters
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-4 pt-4 mt-4 border-t border-gray-200">
                    <button type="button" id="cancelBtn" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="generateReportBtn" class="btn-primary px-5 py-2 text-white rounded-lg font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- View Report Modal -->
<div id="viewReportModal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="modal-content bg-white rounded-xl w-full max-w-4xl max-h-[90vh] relative transform transition-all duration-300 scale-100 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Report Preview</h2>
                        <p class="text-blue-100 text-sm">Review report information before download</p>
                    </div>
                </div>
                <button id="closeViewReportModal" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <!-- Loading State -->
            <div id="reportLoading" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading report details...</p>
            </div>

            <!-- Report Content -->
            <div id="reportContent" class="hidden">
                <!-- Report Header -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Report ID:</span>
                                    <span id="reportId" class="text-gray-900"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Report Type:</span>
                                    <span id="reportType" class="text-gray-900"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Status:</span>
                                    <span id="reportStatus" class=""></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Created Date:</span>
                                    <span id="reportCreatedDate" class="text-gray-900"></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Requestor Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Requestor Type:</span>
                                    <span id="requestorType" class="text-gray-900"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Name:</span>
                                    <span id="requestorName" class="text-gray-900"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-medium">Organization:</span>
                                    <span id="requestorOrganization" class="text-gray-900"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Date Range & Purpose</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Start Date:</span>
                            <span id="reportStartDate" class="text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">End Date:</span>
                            <span id="reportEndDate" class="text-gray-900"></span>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <span class="text-gray-600 font-medium">Purpose:</span>
                        <p id="reportPurpose" class="text-gray-700 mt-2 whitespace-pre-wrap"></p>
                    </div>
                </div>

                <!-- Data Table Preview -->
                <div class="bg-white border rounded-lg overflow-hidden">
                    <div class="bg-[#242F41] text-white px-6 py-4">
                        <h3 class="text-lg font-semibold">Downloaded Data Preview</h3>
                        <p class="text-gray-300 text-sm">This is what was included in the PDF download</p>
                    </div>
                    <div class="p-6">
                        <div id="dataTableContainer" class="overflow-x-auto">
                            <!-- Data table will be inserted here based on report type -->
                        </div>
                        <div id="noDataMessage" class="text-center py-8 text-gray-500 hidden">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No data found for the specified criteria</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div id="reportError" class="text-center py-8 hidden">
                <div class="flex flex-col items-center">
                    <i class="fas fa-exclamation-triangle text-red-300 text-4xl mb-2"></i>
                    <p class="text-red-500 font-medium">Failed to load report details</p>
                    <p class="text-sm text-red-400">Please try again later</p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200 px-6 pb-6">
            <button id="closeViewReportModalBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Requestors Modal -->
<div id="requestorsModal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="modal-content bg-white rounded-xl w-full max-w-4xl relative transform transition-all duration-300 scale-100">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Requestors List</h2>
                        <p class="text-blue-100 text-sm">All registered requestors</p>
                    </div>
                </div>
                <button id="closeRequestorsModal" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="flex gap-2">
                    <input
                        type="text"
                        id="requestorSearch"
                        class="search-input flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="Search requestors..."
                    >
                    <button
                        id="searchRequestorsBtn"
                        class="btn-primary px-4 py-2.5 text-white rounded-lg flex items-center text-sm font-medium transition-all duration-200"
                        title="Search"
                    >
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div id="requestorsLoading" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading requestors...</p>
            </div>

            <!-- Requestors Table -->
            <div id="requestorsTableContainer" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                        </tr>
                    </thead>
                    <tbody id="requestorsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Requestors will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- No Results Message -->
            <div id="noRequestorsMessage" class="text-center py-8 hidden">
                <div class="flex flex-col items-center">
                    <i class="fas fa-users text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500">No requestors found.</p>
                    <p class="text-sm text-gray-400">Try adjusting your search criteria.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="requestorsPagination" class="mt-6 flex items-center justify-between">
                <div id="requestorsInfo" class="text-sm text-gray-700"></div>
                <div id="requestorsPaginationButtons" class="flex gap-2"></div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200 px-6 pb-6">
            <button id="closeRequestorsModalBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Report Preview Modal -->
<div id="reportPreviewModal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="modal-content bg-white rounded-xl w-full max-w-5xl max-h-[90vh] relative transform transition-all duration-300 scale-100 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#242F41] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Report Preview</h2>
                        <p class="text-blue-100 text-sm">Review data before downloading PDF</p>
                    </div>
                </div>
                <button id="closeReportPreviewModal" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <!-- Report Information -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Report Type:</span>
                                <span id="previewReportType" class="text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Date Range:</span>
                                <span id="previewDateRange" class="text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Generated:</span>
                                <span id="previewGeneratedDate" class="text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Requestor Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Requestor:</span>
                                <span id="previewRequestorName" class="text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Organization:</span>
                                <span id="previewOrganization" class="text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Purpose:</span>
                                <span id="previewPurpose" class="text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Data Preview -->
            <div class="bg-white border rounded-lg overflow-hidden">
                <div class="bg-[#242F41] text-white px-6 py-4">
                    <h3 class="text-lg font-semibold">Data Preview</h3>
                    <p class="text-gray-300 text-sm">Sample of the data that will be included in the PDF</p>
                </div>
                <div class="p-6">
                    <div id="previewDataContainer" class="overflow-x-auto">
                        <!-- Data table will be inserted here -->
                    </div>
                    <div id="previewNoDataMessage" class="text-center py-8 text-gray-500 hidden">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>No data found for the specified criteria</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between space-x-4 pt-4 mt-6 border-t border-gray-200 px-5 pb-5">
                <button id="closeReportPreviewModalBtn" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                    Cancel
                </button>
                <button id="downloadReportPdfBtn" class="btn-primary px-5 py-2 text-white rounded-lg font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-download"></i>
                    Download PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to search requestors (placeholder)
function searchRequestors() {
    const searchTerm = document.getElementById('requestorSearch')?.value.toLowerCase() || '';
    
    if (!searchTerm) {
        showToast('Please enter a search term', 'warning');
        return;
    }
    
    showToast(`Searching for: ${searchTerm}`, 'warning');
    // This would typically filter the requestors table
    // For now, we'll just show a message
}

// Function to close requestors modal
function closeRequestorsModal() {
    const modal = document.getElementById('tempRequestorsModal');
    if (modal) {
        modal.remove();
    }
}

// Function to filter requestors in the modal
function filterRequestors() {
    const searchTerm = document.getElementById('requestorSearchInput')?.value.toLowerCase() || '';
    const rows = document.querySelectorAll('.requestor-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const org = row.getAttribute('data-org') || '';
        
        if (name.includes(searchTerm) || org.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Function to clear requestor search
function clearRequestorSearch() {
    const searchInput = document.getElementById('requestorSearchInput');
    if (searchInput) {
        searchInput.value = '';
        filterRequestors(); // Show all rows
    }
}


// Function to show field error
function showFieldError(errorId, message) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        // Handle both string and array messages
        const displayMessage = Array.isArray(message) ? message[0] : message;
        errorElement.textContent = displayMessage;
        errorElement.style.display = 'block';
        errorElement.classList.remove('hidden');
        errorElement.style.visibility = 'visible';
        errorElement.style.opacity = '1';
    }

    // Add red border to the input field
    const fieldId = errorId.replace('_error', '');
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('form-field-invalid');
        field.classList.remove('form-field-valid');
    }
}

// Function to clear field error
function clearFieldError(errorId) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.style.display = 'none';
        errorElement.classList.add('hidden');
        errorElement.style.visibility = 'hidden';
        errorElement.style.opacity = '0';
        errorElement.textContent = '';
    }

    // Remove error styling from the input field
    const fieldId = errorId.replace('_error', '');
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('form-field-invalid');
        field.classList.remove('form-field-valid');
    }
}

// Function to clear all field errors
function clearAllFieldErrors() {
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.style.display = 'none';
        element.classList.add('hidden');
        element.style.visibility = 'hidden';
        element.style.opacity = '0';
        element.textContent = '';
    });

    // Clear styling from all input fields
    const fields = document.querySelectorAll('#requestDataForm input, #requestDataForm select, #requestDataForm textarea');
    fields.forEach(field => {
        field.classList.remove('form-field-invalid');
        field.classList.remove('form-field-valid');
    });
}

// Function to show field success
function showFieldSuccess(fieldId, message = '') {
    const successElement = document.getElementById(fieldId + '_success');
    if (successElement) {
        successElement.textContent = message;
        successElement.style.display = 'flex';
    }

    // Add success styling to the input field
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('form-field-invalid');
        field.classList.add('form-field-valid');
    }
}

// Function to clear field success
function clearFieldSuccess(fieldId) {
    const successElement = document.getElementById(fieldId + '_success');
    if (successElement) {
        successElement.style.display = 'none';
        successElement.textContent = '';
    }

    // Remove success styling from the input field
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('form-field-valid');
    }
}

// Real-time validation setup for request data form
function setupRequestDataFormValidation() {
    // Real-time validation for requestor type
    const requestorTypeRadios = document.querySelectorAll('input[name="requestor_type"]');
    if (requestorTypeRadios.length > 0) {
        requestorTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                clearFieldError('requestor_type_error');
                showFieldSuccess('requestor_type', 'Requestor type selected');
                    
                // Clear errors for dependent fields
                clearFieldError('requestor_id_error');
                clearFieldError('first_name_error');
                clearFieldError('last_name_error');
                clearFieldError('organization_error');
            });
        });
    }

    // Real-time validation for old requestor selection
    const requestorSelect = document.querySelector('select[name="requestor_id"]');
    if (requestorSelect) {
        requestorSelect.addEventListener('change', function() {
            const value = this.value.trim();
            clearFieldError('requestor_id_error');
            clearFieldSuccess('requestor_id');
                
            // Real-time validation
            if (!value) {
                showFieldError('requestor_id_error', 'Please select an existing requestor from the dropdown.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('requestor_id_error');
                showFieldSuccess('requestor_id', 'Requestor selected');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            }
        });
            
        // Add blur event to check for empty field
        requestorSelect.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('requestor_id_error', 'Please select an existing requestor from the dropdown.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Real-time validation for first name
    const firstNameInput = document.querySelector('input[name="first_name"]');
    if (firstNameInput) {
        firstNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('first_name_error');
            clearFieldSuccess('first_name');
                
            // Real-time validation
            if (!value) {
                showFieldError('first_name_error', 'First name is required for new requestor.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 2) {
                showFieldError('first_name_error', 'First name must be at least 2 characters long.');
                this.classList.add('form-field-invalid');
            } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                showFieldError('first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('first_name_error');
                showFieldSuccess('first_name', 'Valid first name');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            }
        });
            
        // Add blur event to check for empty field
        firstNameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('first_name_error', 'First name is required for new requestor.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Real-time validation for middle name
    const middleNameInput = document.querySelector('input[name="middle_name"]');
    if (middleNameInput) {
        middleNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('middle_name_error');
            clearFieldSuccess('middle_name');
                
            // Real-time validation (optional field)
            if (value && !/^[A-Za-z\s\-\']+$/.test(value)) {
                showFieldError('middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
                this.classList.add('form-field-invalid');
            } else if (value) {
                // Valid format - show success
                clearFieldError('middle_name_error');
                showFieldSuccess('middle_name', 'Valid middle name');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            } else {
                // Clear validation for empty optional field
                clearFieldError('middle_name_error');
                clearFieldSuccess('middle_name');
                this.classList.remove('form-field-invalid');
                this.classList.remove('form-field-valid');
            }
        });
    }

    // Real-time validation for last name
    const lastNameInput = document.querySelector('input[name="last_name"]');
    if (lastNameInput) {
        lastNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('last_name_error');
            clearFieldSuccess('last_name');
                
            // Real-time validation
            if (!value) {
                showFieldError('last_name_error', 'Last name is required for new requestor.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 2) {
                showFieldError('last_name_error', 'Last name must be at least 2 characters long.');
                this.classList.add('form-field-invalid');
            } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                showFieldError('last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('last_name_error');
                showFieldSuccess('last_name');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            }
        });
            
        // Add blur event to check for empty field
        lastNameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('last_name_error', 'Last name is required for new requestor.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Real-time validation for organization
    const organizationInput = document.querySelector('input[name="organization"]');
    if (organizationInput) {
        organizationInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('organization_error');
            clearFieldSuccess('organization');
                
            // Real-time validation
            if (!value) {
                showFieldError('organization_error', 'Organization name is required for new requestor.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 2) {
                showFieldError('organization_error', 'Organization name must be at least 2 characters long.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('organization_error');
                showFieldSuccess('organization');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            }
        });
            
        // Add blur event to check for empty field
        organizationInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('organization_error', 'Organization name is required for new requestor.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Real-time validation for report type
    const reportTypeSelect = document.querySelector('select[name="report_type"]');
    if (reportTypeSelect) {
        reportTypeSelect.addEventListener('change', function() {
            const value = this.value.trim();
            clearFieldError('report_type_error');
            clearFieldSuccess('report_type');
                
            // Real-time validation
            if (!value) {
                showFieldError('report_type_error', 'Please select a report type to generate.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('report_type_error');
                showFieldSuccess('report_type');
                this.classList.remove('form-field-invalid');
                this.classList.add('form-field-valid');
            }
        });
            
        // Add blur event to check for empty field
        reportTypeSelect.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('report_type_error', 'Please select a report type to generate.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Real-time validation for start date
    const startDateInput = document.querySelector('input[name="start_date"]');
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            const value = this.value.trim();
            clearFieldError('start_date_error');
            clearFieldSuccess('start_date');
                
            // Real-time validation
            if (value) {
                const start = new Date(value);
                const today = new Date();
                today.setHours(23, 59, 59, 999); // Set to end of today
                    
                if (start > today) {
                    showFieldError('start_date_error', 'Start date cannot be in the future.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Check if end date is also set and validate date range
                    const endDateInput = document.querySelector('input[name="end_date"]');
                    if (endDateInput && endDateInput.value.trim()) {
                        const end = new Date(endDateInput.value.trim());
                        if (start > end) {
                            showFieldError('start_date_error', 'Start date must be before or equal to end date.');
                            this.classList.add('form-field-invalid');
                        } else {
                            // Valid format - show success
                            clearFieldError('start_date_error');
                            showFieldSuccess('start_date');
                            this.classList.remove('form-field-invalid');
                            this.classList.add('form-field-valid');
                                
                            // Also clear end date error if it was set
                            clearFieldError('end_date_error');
                            showFieldSuccess('end_date');
                            endDateInput.classList.remove('form-field-invalid');
                            endDateInput.classList.add('form-field-valid');
                        }
                    } else {
                        // Valid format - show success
                        clearFieldError('start_date_error');
                        showFieldSuccess('start_date', 'Valid start date');
                        this.classList.remove('form-field-invalid');
                        this.classList.add('form-field-valid');
                    }
                }
            } else {
                // Clear validation for empty field
                clearFieldError('start_date_error');
                clearFieldSuccess('start_date');
                this.classList.remove('form-field-invalid');
                this.classList.remove('form-field-valid');
            }
        });
    }

    // Real-time validation for end date
    const endDateInput = document.querySelector('input[name="end_date"]');
    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            const value = this.value.trim();
            clearFieldError('end_date_error');
            clearFieldSuccess('end_date');
                
            // Real-time validation
            if (value) {
                const end = new Date(value);
                const today = new Date();
                today.setHours(23, 59, 59, 999); // Set to end of today
                    
                if (end > today) {
                    showFieldError('end_date_error', 'End date cannot be in the future.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Check if start date is also set and validate date range
                    const startDateInput = document.querySelector('input[name="start_date"]');
                    if (startDateInput && startDateInput.value.trim()) {
                        const start = new Date(startDateInput.value.trim());
                        if (start > end) {
                            showFieldError('end_date_error', 'End date must be after or equal to start date.');
                            this.classList.add('form-field-invalid');
                        } else {
                            // Valid format - show success
                            clearFieldError('end_date_error');
                            showFieldSuccess('end_date');
                            this.classList.remove('form-field-invalid');
                            this.classList.add('form-field-valid');
                                
                            // Also clear start date error if it was set
                            clearFieldError('start_date_error');
                            showFieldSuccess('start_date', 'Valid start date');
                            startDateInput.classList.remove('form-field-invalid');
                            startDateInput.classList.add('form-field-valid');
                        }
                    } else {
                        // Valid format - show success
                        clearFieldError('end_date_error');
                        showFieldSuccess('end_date');
                        this.classList.remove('form-field-invalid');
                        this.classList.add('form-field-valid');
                    }
                }
            } else {
                // Clear validation for empty field
                clearFieldError('end_date_error');
                clearFieldSuccess('end_date');
                this.classList.remove('form-field-invalid');
                this.classList.remove('form-field-valid');
            }
        });
    }

    // Real-time validation for purpose
    const purposeTextarea = document.querySelector('textarea[name="purpose"]');
    if (purposeTextarea) {
        purposeTextarea.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('purpose_error');
            clearFieldSuccess('purpose');
                
            // Real-time validation (only required when requestor is selected)
            const requestorType = document.querySelector('input[name="requestor_type"]:checked')?.value;
            if (requestorType !== 'no_requestor') {
                if (!value) {
                    showFieldError('purpose_error', 'Purpose is required when a requestor is specified.');
                    this.classList.add('form-field-invalid');
                } else if (value.length < 10) {
                    showFieldError('purpose_error', 'Purpose must be at least 10 characters long.');
                    this.classList.add('form-field-invalid');
                } else if (value.length > 500) {
                    showFieldError('purpose_error', 'Purpose must not exceed 500 characters.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Valid format - show success
                    clearFieldError('purpose_error');
                    showFieldSuccess('purpose', 'Valid purpose');
                    this.classList.remove('form-field-invalid');
                    this.classList.add('form-field-valid');
                }
            } else {
                // Clear validation for no requestor case
                clearFieldError('purpose_error');
                clearFieldSuccess('purpose');
                this.classList.remove('form-field-invalid');
                this.classList.remove('form-field-valid');
            }
                
            // Update character counter
            const countElement = document.getElementById('purpose_count');
            if (countElement) {
                countElement.textContent = value.length;
                if (value.length > 450) {
                    countElement.className = 'text-red-500 font-medium';
                } else if (value.length > 400) {
                    countElement.className = 'text-yellow-500 font-medium';
                } else {
                    countElement.className = 'text-gray-500';
                }
            }
        });
            
        // Add blur event to check for empty field
        purposeTextarea.addEventListener('blur', function() {
            const requestorType = document.querySelector('input[name="requestor_type"]:checked')?.value;
            if (requestorType !== 'no_requestor' && !this.value.trim()) {
                showFieldError('purpose_error', 'Purpose is required when a requestor is specified.');
                this.classList.add('form-field-invalid');
            }
        });
    }
}

// Function to view report details
function viewReport(reportId) {
    const modal = document.getElementById('viewReportModal');
    const loading = document.getElementById('reportLoading');
    const content = document.getElementById('reportContent');
    const error = document.getElementById('reportError');
    
    // Show modal and loading state
    modal.classList.remove('hidden');
    loading.classList.remove('hidden');
    content.classList.add('hidden');
    error.classList.add('hidden');
    
    // Store report ID for download
    window.currentReportId = reportId;
    
    fetch(`{{ url('reports') }}/${reportId}`)
        .then(response => response.json())
        .then(data => {
            loading.classList.add('hidden');
            
            if (data.success && data.report) {
                displayReportDetails(data.report);
                content.classList.remove('hidden');
            } else {
                error.classList.remove('hidden');
                showToast('Failed to load report details', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loading.classList.add('hidden');
            error.classList.remove('hidden');
            showToast('An error occurred while loading report details', 'error');
        });
}

// Function to display report details in modal
function displayReportDetails(report) {
    // Report Information
    document.getElementById('reportId').textContent = `RPT${String(report.report_id).padStart(4, '0')}`;
    document.getElementById('reportType').textContent = report.report_type ? report.report_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A';
    
    // Status with styling
    const statusElement = document.getElementById('reportStatus');
    const status = report.status || 'pending';
    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    
    // Apply status-specific styling
    statusElement.className = '';
    if (status === 'pending') {
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
    } else if (status === 'approved') {
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    } else if (status === 'completed') {
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    } else if (status === 'rejected') {
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
    }
    
    document.getElementById('reportCreatedDate').textContent = report.created_at ? new Date(report.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : 'N/A';
    
    // Requestor Details
    const requestorTypeText = report.requestor_type === 'no_requestor' ? 'Internal Report' : 
                             report.requestor_type === 'old_requestor' ? 'Existing Requestor' : 
                             report.requestor_type === 'new_requestor' ? 'New Requestor' : 'N/A';
    document.getElementById('requestorType').textContent = requestorTypeText;
    
    let requestorName = 'N/A';
    if (report.requestor) {
        const names = [report.requestor.first_name, report.requestor.middle_name, report.requestor.last_name]
            .filter(name => name && name.trim() !== '')
            .join(' ');
        requestorName = names || 'N/A';
    } else if (report.requestor_type === 'no_requestor') {
        requestorName = 'Internal Request';
    }
    document.getElementById('requestorName').textContent = requestorName;
    
    document.getElementById('requestorOrganization').textContent = report.organization || 'N/A';
    
    // Date Range
    document.getElementById('reportStartDate').textContent = report.start_date ? new Date(report.start_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }) : 'Not specified';
    
    document.getElementById('reportEndDate').textContent = report.end_date ? new Date(report.end_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }) : 'Not specified';
    
    // Purpose
    document.getElementById('reportPurpose').textContent = report.purpose || 'No purpose specified';
    
    // Load and display data table based on report type
    loadReportData(report);
}

// Function to load and display data based on report type
function loadReportData(report) {
    const dataContainer = document.getElementById('dataTableContainer');
    const noDataMessage = document.getElementById('noDataMessage');
    
    // Show information about what was downloaded instead of trying to fetch live data
    const reportType = report.report_type;
    let tableHTML = '';
    
    // Handle different report type formats - both form values and PDF logger service values
    if (reportType === 'rainfall_history' || 
        reportType === 'Rainfall History' || 
        reportType === 'Rainfall History Report' ||
        reportType.toLowerCase().includes('rainfall')) {
        tableHTML = createRainfallPreviewTable(report);
    } else if (reportType === 'message_history' || 
               reportType === 'Message History' || 
               reportType === 'Message Management Report' ||
               reportType.toLowerCase().includes('message')) {
        tableHTML = createMessagePreviewTable(report);
    } else if (reportType === 'device_info' || 
               reportType === 'Device Information' || 
               reportType === 'Device Management Report' ||
               reportType.toLowerCase().includes('device')) {
        tableHTML = createDevicePreviewTable(report);
    } else if (reportType === 'contacts_info' || 
               reportType === 'Contacts Information Report' ||
               reportType.toLowerCase().includes('contacts')) {
        tableHTML = createContactsPreviewTable(report);
    } else if (reportType === 'user_management' || 
               reportType === 'User Management Report' ||
               reportType.toLowerCase().includes('user')) {
        tableHTML = createUsersPreviewTable(report);
    } else if (reportType === 'reports_summary' || 
               reportType === 'Reports Summary Report' ||
               reportType.toLowerCase().includes('reports summary')) {
        tableHTML = createReportsSummaryPreviewTable(report);
    } else {
        // For other report types, show a message
        dataContainer.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-info-circle text-3xl mb-2"></i>
                <p>This report type (${reportType || 'Unknown'}) contains system-generated data.</p>
                <p class="text-sm">The downloaded PDF contains the complete data for this report.</p>
            </div>
        `;
        return;
    }
    
    if (tableHTML) {
        dataContainer.innerHTML = tableHTML;
    } else {
        showNoDataMessage();
    }
}

// Function to create rainfall history preview table
function createRainfallPreviewTable(report) {
    const dateRange = getDateRangeText(report.start_date, report.end_date);
    
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Rainfall History Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains rainfall data ${dateRange}</p>
        </div>
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Data Included:</strong> Date/Time, Device ID, Location, Rainfall Amount (mm), Temperature, Intensity Level
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rainfall (mm)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperature (°C)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete rainfall data ${dateRange} was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to create message history preview table
function createMessagePreviewTable(report) {
    const dateRange = getDateRangeText(report.start_date, report.end_date);
    
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Message History Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains message data ${dateRange}</p>
        </div>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Data Included:</strong> Date/Time, Device ID, Message Content, Intensity Level, Status, Recipient
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete message history ${dateRange} was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to create device info preview table
function createDevicePreviewTable(report) {
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Device Information Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains current device information</p>
        </div>
        <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-purple-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-purple-700">
                        <strong>Data Included:</strong> Device ID, Serial Number, Location, Status, Installation Date, Coordinates, Latest Reading
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installation Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete device information was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to create contacts info preview table
function createContactsPreviewTable(report) {
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Contacts Information Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains contact information</p>
        </div>
        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-indigo-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-indigo-700">
                        <strong>Data Included:</strong> Contact Name, Phone Number, Position, Location, Emergency Contact Status
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emergency Contact</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete contacts information was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to create users management preview table
function createUsersPreviewTable(report) {
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">User Management Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains user management information</p>
        </div>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>Data Included:</strong> User ID, Username, Email, Role, Registration Date, Last Login
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete user management data was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to create reports summary preview table
function createReportsSummaryPreviewTable(report) {
    const dateRange = getDateRangeText(report.start_date, report.end_date);
    
    return `
        <div class="mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Reports Summary Data</h4>
            <p class="text-gray-600 text-sm">Downloaded PDF contains reports summary ${dateRange}</p>
        </div>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Data Included:</strong> Report ID, Report Type, Requestor, Organization, Status, Date Created, Purpose
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500"></i>
                            <p class="font-medium">Data exported to PDF</p>
                            <p class="text-sm">The complete reports summary ${dateRange} was included in the downloaded PDF file.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

// Function to show no data message
function showNoDataMessage() {
    const dataContainer = document.getElementById('dataTableContainer');
    const noDataMessage = document.getElementById('noDataMessage');
    
    dataContainer.innerHTML = '';
    noDataMessage.classList.remove('hidden');
}

// Function to close view report modal
function closeViewReportModalFunc() {
    const modal = document.getElementById('viewReportModal');
    modal.classList.add('hidden');
    window.currentReportId = null;
}

// Function to update report status
function updateReportStatus(reportId) {
    const status = prompt('Enter new status (pending, approved, completed, rejected):');
    if (status && ['pending', 'approved', 'completed', 'rejected'].includes(status)) {
        fetch(`{{ url('reports') }}/${reportId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Report status updated successfully', 'success');
                window.location.reload();
            } else {
                showToast('Failed to update report status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while updating report status', 'error');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const requestDataBtn = document.getElementById('requestDataBtn');
    const requestDataModal = document.getElementById('requestDataModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const generateReportBtn = document.getElementById('generateReportBtn');
    const requestDataForm = document.getElementById('requestDataForm');
    const viewDataBtn = document.getElementById('viewDataBtn');
    const exportBtn = document.getElementById('exportBtn');
    const reportType = document.getElementById('report_type');
    
    // Requestors Modal Elements
    const viewRequestorsBtn = document.getElementById('viewRequestorsBtn');
    const requestorsModal = document.getElementById('requestorsModal');
    const closeRequestorsModal = document.getElementById('closeRequestorsModal');
    const closeRequestorsModalBtn = document.getElementById('closeRequestorsModalBtn');
    const requestorSearch = document.getElementById('requestorSearch');
    const searchRequestorsBtn = document.getElementById('searchRequestorsBtn');
    
    // View Report Modal Elements
    const viewReportModal = document.getElementById('viewReportModal');
    const closeViewReportModal = document.getElementById('closeViewReportModal');
    const closeViewReportModalBtn = document.getElementById('closeViewReportModalBtn');
    
    let currentRequestorsPage = 1;
    let currentSearchTerm = '';

    // Error handling functions - REPLACED with unified banner system
    //     // Auto remove after 5 seconds
    //     setTimeout(() => {
    //         if (banner.parentNode) {
    //             banner.remove();
    //         }
    //     }, 5000);
    // }

    function showFieldError(errorId, message) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            // Handle both string and array messages
            const displayMessage = Array.isArray(message) ? message[0] : message;
            errorElement.textContent = displayMessage;
            errorElement.style.display = 'block';
            errorElement.classList.remove('hidden');
            errorElement.style.visibility = 'visible';
            errorElement.style.opacity = '1';
        }

        // Add red border to the input field
        const fieldId = errorId.replace('_error', '');
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.add('form-field-invalid');
            field.classList.remove('form-field-valid');
        }

        // Don't show error icon - remove this functionality
        const iconId = fieldId + '_icon';
        const icon = document.getElementById(iconId);
        if (icon) {
            icon.style.display = 'none'; // Hide the icon instead of showing error icon
        }
    }

    function clearFieldError(errorId) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.classList.add('hidden');
            errorElement.style.visibility = 'hidden';
            errorElement.style.opacity = '0';
            errorElement.textContent = '';
        }

        // Remove error styling from the input field
        const fieldId = errorId.replace('_error', '');
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('form-field-invalid');
            field.classList.remove('form-field-valid');
        }

        // Don't show any icons
        const iconId = fieldId + '_icon';
        const icon = document.getElementById(iconId);
        if (icon) {
            icon.style.display = 'none'; // Hide the icon completely
        }
    }

    // Helper function to clear error banner
    function clearErrorBanner() {
        const errorBanner = document.getElementById('errorBanner');
        if (errorBanner) {
            errorBanner.remove();
        }
    }

    // Function to show field success
    function showFieldSuccess(fieldId, message = 'Valid') {
        const successElement = document.getElementById(fieldId + '_success');
        if (successElement) {
            successElement.textContent = message;
            successElement.style.display = 'flex';
        }

        // Add success styling to the input field
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('form-field-invalid');
            field.classList.add('form-field-valid');
        }

        // Don't show success icon
        const iconId = fieldId + '_icon';
        const icon = document.getElementById(iconId);
        if (icon) {
            icon.style.display = 'none'; // Hide the icon completely
        }
    }

    // Function to clear field success
    function clearFieldSuccess(fieldId) {
        const successElement = document.getElementById(fieldId + '_success');
        if (successElement) {
            successElement.style.display = 'none';
            successElement.textContent = '';
        }

        // Remove success styling from the input field
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('form-field-valid');
        }

        // Don't show any icons
        const iconId = fieldId + '_icon';
        const icon = document.getElementById(iconId);
        if (icon) {
            icon.style.display = 'none'; // Hide the icon completely
        }
    }

    // Toast notification functions
    function showToast(message, type = 'success') {
        // Use unified banner system
        if (typeof window.showBanner === 'function') {
            window.showBanner(type, message, 3000);
        } else {
            console.warn('Unified banner system not available');
        }
    }
    
    // Toast handling is now managed by the unified banner system
    
    // Function to show success alert - REPLACED with unified banner system
    // function showSuccessAlert(message) {
    //     // Remove any existing alerts
    //     const existingAlert = document.querySelector('.success-alert');
    //     if (existingAlert) {
    //         existingAlert.remove();
    //     }
    //
    //     // Ensure the message is not empty
    //     if (!message || message.trim() === '') {
    //         return;
    //     }
    //
    //     // Determine styling based on message type
    //     let bgColor, borderColor, textColor, iconClass;
    //     if (message.toLowerCase().includes('deleted') || message.toLowerCase().includes('delete')) {
    //         // Red styling for delete operations
    //         bgColor = 'bg-red-100';
    //         borderColor = 'border-red-400';
    //         textColor = 'text-red-700';
    //         iconClass = 'fas fa-trash-alt';
    //     } else {
    //         // Green styling for add/update operations
    //         bgColor = 'bg-green-100';
    //         borderColor = 'border-green-400';
    //         textColor = 'text-green-700';
    //         iconClass = 'fas fa-check-circle';
    //     }
    //
    //     // Create alert element - centered at top with 80px offset
    //     const alert = document.createElement('div');
    //     alert.className = `success-alert fixed left-1/2 transform -translate-x-1/2 ${bgColor} ${borderColor} ${textColor} px-6 py-4 rounded-lg shadow-lg z-[9999999] transition-all duration-300 opacity-0 translate-y-[-20px]`;
    //     alert.style.top = '80px'; // 80px from top as per user preference
    //     alert.innerHTML = `
    //         <div class="flex items-center gap-3">
    //             <i class="${iconClass} text-lg"></i>
    //             <span class="font-medium">${message}</span>
    //         </div>
    //     `;
    //
    //     // Add to page
    //     document.body.appendChild(alert);
    //
    //     // Animate in
    //     setTimeout(() => {
    //         alert.classList.remove('opacity-0', 'translate-y-[-20px]');
    //         alert.classList.add('opacity-100', 'translate-y-0');
    //     }, 100);
    //
    //     // Auto remove after 5 seconds
    //     setTimeout(() => {
    //         alert.classList.add('opacity-0', 'translate-y-[-20px]');
    //         setTimeout(() => {
    //             if (alert.parentNode) {
    //                 alert.remove();
    //             }
    //         }, 300);
    //     }, 5000);
    // }

    // Modal functions
    function openModal() {
        requestDataModal.classList.remove('hidden');
        clearAllFieldErrors();
    }

    function closeModalFunc() {
        requestDataModal.classList.add('hidden');
    }
    function cancelModalFunc() {
        requestDataModal.classList.add('hidden');
        clearAllFieldErrors();
        requestDataForm.reset();
    }

    // Event listeners
    requestDataBtn.addEventListener('click', openModal);
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', cancelModalFunc);
    



    // Prevent modal from closing when clicking inside
    requestDataModal.querySelector('.modal-content').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // View data functionality
    if (viewDataBtn) {
        viewDataBtn.addEventListener('click', function() {
            const selectedType = reportType.value;
            
            if (!selectedType) {
                showToast('Please select a report type', 'warning');
                return;
            }

            // Navigate based on selected report type
            if (selectedType === 'rainfall_history') {
                window.location.href = '{{ route("history") }}';
                return;
            }
            if (selectedType === 'message_history') {
                window.location.href = '{{ route("messages.index") }}';
                return;
            }
            if (selectedType === 'device_info') {
                window.location.href = '{{ route("devices.index") }}';
                return;
            }
        });
    }

    // Export functionality
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Get current search and filter parameters
            const searchParams = new URLSearchParams();
            
            // Get search parameter
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim()) {
                searchParams.append('search', searchInput.value.trim());
            }
            
            // Get status filter parameter
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect && statusSelect.value) {
                searchParams.append('status', statusSelect.value);
            }
            
            // Construct URL with parameters
            let url = '{{ route("reports.export_pdf") }}';
            if (searchParams.toString()) {
                url += '?' + searchParams.toString();
            }
            
            // Create temporary link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    // Form validation functions
    function validateReportsForm() {
        const errors = {};
        const requestorType = document.querySelector('input[name="requestor_type"]:checked')?.value;
        
        
        // Old requestor validation
        if (requestorType === 'old_requestor') {
            const requestorId = document.querySelector('select[name="requestor_id"]').value;
            if (!requestorId) {
                errors.requestor_id = 'Please select an existing requestor from the dropdown.';
            }
        }
        
        // New requestor validation
        if (requestorType === 'new_requestor') {
            const firstName = document.querySelector('input[name="first_name"]').value.trim();
            const lastName = document.querySelector('input[name="last_name"]').value.trim();
            const organization = document.querySelector('input[name="organization"]').value.trim();
            
            if (!firstName) {
                errors.first_name = 'First name is required for new requestor.';
            } else if (firstName.length < 2) {
                errors.first_name = 'First name must be at least 2 characters long.';
            } else if (!/^[A-Za-z\s\-\']+$/.test(firstName)) {
                errors.first_name = 'First name may only contain letters, spaces, hyphens, and apostrophes.';
            }
            
            if (!lastName) {
                errors.last_name = 'Last name is required for new requestor.';
            } else if (lastName.length < 2) {
                errors.last_name = 'Last name must be at least 2 characters long.';
            } else if (!/^[A-Za-z\s\-\']+$/.test(lastName)) {
                errors.last_name = 'Last name may only contain letters, spaces, hyphens, and apostrophes.';
            }
            
            if (!organization) {
                errors.organization = 'Organization name is required for new requestor.';
            } else if (organization.length < 2) {
                errors.organization = 'Organization name must be at least 2 characters long.';
            }

            // Middle name validation (optional but if provided should be valid)
            const middleName = document.querySelector('input[name="middle_name"]').value.trim();
            if (middleName && !/^[A-Za-z\s\-\']+$/.test(middleName)) {
                errors.middle_name = 'Middle name may only contain letters, spaces, hyphens, and apostrophes.';
            }
        }
        
        // Report type validation
        const reportType = document.querySelector('select[name="report_type"]').value;
        if (!reportType) {
            errors.report_type = 'Please select a report type to generate.';
        }
        
        // Date validation
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const today = new Date();
            today.setHours(23, 59, 59, 999); // Set to end of today
            
            if (start > today) {
                errors.start_date = 'Start date cannot be in the future.';
            }
            
            if (end > today) {
                errors.end_date = 'End date cannot be in the future.';
            }
            
            if (start > end) {
                errors.start_date = 'Start date must be before or equal to end date.';
                errors.end_date = 'End date must be after or equal to start date.';
            }
        }
        
        // Purpose validation - only required when requestor is selected
        if (requestorType !== 'no_requestor') {
            const purpose = document.querySelector('textarea[name="purpose"]').value.trim();
            if (!purpose) {
                errors.purpose = 'Purpose is required when a requestor is specified.';
            } else if (purpose.length < 10) {
                errors.purpose = 'Purpose must be at least 10 characters long.';
            } else if (purpose.length > 500) {
                errors.purpose = 'Purpose must not exceed 500 characters.';
            }
        }
        
        return errors;
    }

    // Setup real-time validation
    setupRequestDataFormValidation();
    
    // Form submission
    requestDataForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear all previous errors
        clearAllFieldErrors();

        // Client-side validation
        const errors = validateReportsForm();
        
        if (Object.keys(errors).length > 0) {
            // Show validation error using unified banner system
            window.showBanner('error', 'Please fix the validation errors before submitting.', 2000);

            // Display validation errors
            Object.keys(errors).forEach(fieldName => {
                showFieldError(fieldName + '_error', errors[fieldName]);
            });

            return;
        }

        // If validation passes, generate the report with preview
        const formData = new FormData(requestDataForm);
        
        // Form data is being sent to the server for processing
        
        // Show loading state
        const submitBtn = requestDataForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="flex items-center">'+
            '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>'+
            'Generating...' +
            '</div>';
        submitBtn.disabled = true;
        
        // Change URL to generate endpoint for preview
        fetch('{{ route("reports.generate") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error('Validation failed: ' + JSON.stringify(errorData));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close the generate modal
                closeModalFunc();
                
                // Show the preview modal with data
                showReportPreviewModal(data);
                
                // Refresh the reports table to show the newly created report
                setTimeout(refreshReportsTable, 1000);
            } else {
                // Display server-side validation errors
                if (data.errors) {
                    // Show validation error using unified banner system
                    window.showBanner('error', 'Please fix the validation errors before submitting.', 2000);
                    Object.keys(data.errors).forEach(fieldName => {
                        showFieldError(fieldName + '_error', data.errors[fieldName][0]);
                    });
                } else {
                    // Use unified banner system with 2-second timer
                    if (typeof window.showBanner === 'function') {
                        window.showBanner('error', data.message || 'Failed to generate report', 2000);
                    } else {
                        // Fallback to original implementation if unified system is not available
                        showErrorBanner(data.message || 'Failed to generate report');
                    }
                }
            }
        })
        .catch(error => {
            // Show error using unified banner system
            window.showBanner('error', 'An error occurred while generating the report: ' + error.message, 2000);
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });

    // Real-time validation is handled by setupRequestDataFormValidation function
    // No need for additional event listeners here as they conflict with real-time validation

    // Character counter for purpose field
    const purposeField = document.querySelector('textarea[name="purpose"]');
    const purposeCounter = document.getElementById('purpose_count');
    
    if (purposeField && purposeCounter) {
        purposeField.addEventListener('input', function() {
            const currentLength = this.value.length;
            purposeCounter.textContent = currentLength;
            
            // Update counter color based on length
            if (currentLength > 450) {
                purposeCounter.className = 'text-red-500 font-medium';
            } else if (currentLength > 400) {
                purposeCounter.className = 'text-yellow-500 font-medium';
            } else {
                purposeCounter.className = 'text-gray-500';
            }
        });
    }

    // Helper function to clear error banner
    function clearErrorBanner() {
        const errorBanner = document.getElementById('errorBanner');
        if (errorBanner) {
            errorBanner.remove();
        }
    }

    // Requestors modal functions
    function openRequestorsModal() {
        requestorsModal.classList.remove('hidden');
        loadRequestors(1, '');
    }

    function closeRequestorsModalFunc() {
        requestorsModal.classList.add('hidden');
        currentRequestorsPage = 1;
        currentSearchTerm = '';
        requestorSearch.value = '';
    }

    function loadRequestors(page = 1, search = '') {
        const requestorsLoading = document.getElementById('requestorsLoading');
        const requestorsTableContainer = document.getElementById('requestorsTableContainer');
        const noRequestorsMessage = document.getElementById('noRequestorsMessage');
        const requestorsPagination = document.getElementById('requestorsPagination');

        // Show loading state
        requestorsLoading.classList.remove('hidden');
        requestorsTableContainer.classList.add('hidden');
        noRequestorsMessage.classList.add('hidden');
        requestorsPagination.classList.add('hidden');

        const url = new URL('{{ route("reports.requestors") }}');
        if (search) url.searchParams.append('search', search);
        if (page > 1) url.searchParams.append('page', page);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                requestorsLoading.classList.add('hidden');
                
                if (data.success && data.requestors.length > 0) {
                    displayRequestors(data.requestors);
                    displayRequestorsPagination(data.pagination);
                    requestorsTableContainer.classList.remove('hidden');
                    requestorsPagination.classList.remove('hidden');
                } else {
                    noRequestorsMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                requestorsLoading.classList.add('hidden');
                noRequestorsMessage.classList.remove('hidden');
                showToast('Failed to load requestors', 'error');
            });
    }

    function displayRequestors(requestors) {
        const tbody = document.getElementById('requestorsTableBody');
        tbody.innerHTML = '';

        requestors.forEach(requestor => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const fullName = [requestor.first_name, requestor.middle_name, requestor.last_name]
                .filter(name => name && name.trim() !== '')
                .join(' ');

            const createdDate = new Date(requestor.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    REQ${String(requestor.requestor_id).padStart(4, '0')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${fullName}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${requestor.organization || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${createdDate}
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function displayRequestorsPagination(pagination) {
        const requestorsInfo = document.getElementById('requestorsInfo');
        const requestorsPaginationButtons = document.getElementById('requestorsPaginationButtons');

        // Show pagination info
        if (pagination.from && pagination.to) {
            requestorsInfo.textContent = `Showing ${pagination.from} to ${pagination.to} of ${pagination.total} requestors`;
        } else {
            requestorsInfo.textContent = '';
        }

        // Show pagination buttons
        requestorsPaginationButtons.innerHTML = '';
        
        if (pagination.last_page > 1) {
            // Previous button
            if (pagination.current_page > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50';
                prevBtn.textContent = 'Previous';
                prevBtn.onclick = () => {
                    currentRequestorsPage = pagination.current_page - 1;
                    loadRequestors(currentRequestorsPage, currentSearchTerm);
                };
                requestorsPaginationButtons.appendChild(prevBtn);
            }

            // Page numbers (show first, current-1, current, current+1, last)
            const pagesToShow = new Set();
            pagesToShow.add(1);
            if (pagination.current_page > 1) pagesToShow.add(pagination.current_page - 1);
            pagesToShow.add(pagination.current_page);
            if (pagination.current_page < pagination.last_page) pagesToShow.add(pagination.current_page + 1);
            pagesToShow.add(pagination.last_page);

            let lastShown = 0;
            Array.from(pagesToShow).sort((a, b) => a - b).forEach(page => {
                if (page - lastShown > 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1 text-sm text-gray-500';
                    ellipsis.textContent = '...';
                    requestorsPaginationButtons.appendChild(ellipsis);
                }

                const pageBtn = document.createElement('button');
                pageBtn.className = page === pagination.current_page
                    ? 'px-3 py-1 bg-blue-500 text-white rounded text-sm'
                    : 'px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50';
                pageBtn.textContent = page;
                pageBtn.onclick = () => {
                    currentRequestorsPage = page;
                    loadRequestors(currentRequestorsPage, currentSearchTerm);
                };
                requestorsPaginationButtons.appendChild(pageBtn);
                lastShown = page;
            });

            // Next button
            if (pagination.current_page < pagination.last_page) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50';
                nextBtn.textContent = 'Next';
                nextBtn.onclick = () => {
                    currentRequestorsPage = pagination.current_page + 1;
                    loadRequestors(currentRequestorsPage, currentSearchTerm);
                };
                requestorsPaginationButtons.appendChild(nextBtn);
            }
        }
    }

    function searchRequestors() {
        currentSearchTerm = requestorSearch.value.trim();
        currentRequestorsPage = 1;
        loadRequestors(currentRequestorsPage, currentSearchTerm);
    }

    // Event listeners for requestors modal
    if (viewRequestorsBtn) {
        viewRequestorsBtn.addEventListener('click', openRequestorsModal);
    }
    
    if (closeRequestorsModal) {
        closeRequestorsModal.addEventListener('click', closeRequestorsModalFunc);
    }
    
    if (closeRequestorsModalBtn) {
        closeRequestorsModalBtn.addEventListener('click', closeRequestorsModalFunc);
    }
    
    if (searchRequestorsBtn) {
        searchRequestorsBtn.addEventListener('click', searchRequestors);
    }
    
    if (requestorSearch) {
        requestorSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchRequestors();
            }
        });
    }

    // Close requestors modal when clicking outside
    if (requestorsModal) {
        requestorsModal.addEventListener('click', function(e) {
            if (e.target === requestorsModal) {
                closeRequestorsModalFunc();
            }
        });

        // Prevent modal from closing when clicking inside
        requestorsModal.querySelector('.modal-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // View Report Modal Event Listeners
    if (closeViewReportModal) {
        closeViewReportModal.addEventListener('click', closeViewReportModalFunc);
    }
    
    if (closeViewReportModalBtn) {
        closeViewReportModalBtn.addEventListener('click', closeViewReportModalFunc);
    }
    
    // Close view report modal when clicking outside
    if (viewReportModal) {
        viewReportModal.addEventListener('click', function(e) {
            if (e.target === viewReportModal) {
                closeViewReportModalFunc();
            }
        });

        // Prevent modal from closing when clicking inside
        viewReportModal.querySelector('.modal-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Report Preview Modal Functions
    function showReportPreviewModal(data) {
        const modal = document.getElementById('reportPreviewModal');
        const closeBtn = document.getElementById('closeReportPreviewModal');
        const closeBtnFooter = document.getElementById('closeReportPreviewModalBtn');
        const downloadBtn = document.getElementById('downloadReportPdfBtn');
        
        // Populate modal with data
        document.getElementById('previewReportType').textContent = data.report_info.report_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        document.getElementById('previewDateRange').textContent = data.report_info.date_range;
        document.getElementById('previewGeneratedDate').textContent = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('previewRequestorName').textContent = data.report_info.requestor_name || 'N/A';
        document.getElementById('previewOrganization').textContent = data.report_info.organization || 'N/A';
        document.getElementById('previewPurpose').textContent = data.report_info.purpose || 'No purpose specified';
        
        // Populate data table
        populatePreviewTable(data.report_info.report_type, data.data);
        
        // Store PDF URL for download - ENHANCED: Show success message, close modal, and refresh table
        downloadBtn.onclick = function() {
            // Disable button to prevent multiple clicks
            downloadBtn.disabled = true;
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<div class="flex items-center"><div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Downloading...</div>';
            
            // Add preview_modal parameter to URL to indicate this is from preview modal
            const url = new URL(data.pdf_url);
            url.searchParams.append('preview_modal', '1');
            
            // Use the modified PDF URL from the response
            window.open(url.toString(), '_blank');
            
            // Show success message using the existing banner system
            if (typeof window.showBanner === 'function') {
                window.showBanner('success', 'Report generated successfully!', 3000);
            } else {
                // Fallback to toast if banner system is not available
                showToast('Report generated successfully!', 'success');
            }
            
            // Refresh the reports table to show the newly created report
            refreshReportsTable();
            
            // Close the modal after a short delay
            setTimeout(() => {
                modal.classList.add('hidden');
                // Re-enable button
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = originalText;
            }, 2000);
        };
        
        // Close modal functions
        const closeModal = function() {
            modal.classList.add('hidden');
        };
        
        closeBtn.onclick = closeModal;
        closeBtnFooter.onclick = closeModal;
        
        // Close when clicking outside
        modal.onclick = function(e) {
            if (e.target === modal) {
                closeModal();
            }
        };
        
        // Prevent closing when clicking inside modal content
        modal.querySelector('.modal-content').onclick = function(e) {
            e.stopPropagation();
        };
        
        // Show modal
        modal.classList.remove('hidden');
    }
    
    // Function to refresh the reports table in real-time
    function refreshReportsTable() {
        // Get current search and filter parameters
        const searchParams = new URLSearchParams();
        const searchInput = document.querySelector('input[name="search"]');
        const statusSelect = document.querySelector('select[name="status"]');
        
        if (searchInput && searchInput.value.trim()) {
            searchParams.append('search', searchInput.value.trim());
        }
        
        if (statusSelect && statusSelect.value) {
            searchParams.append('status', statusSelect.value);
        }
        
        // Fetch updated reports data
        fetch('{{ route("reports.index") }}?' + searchParams.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response to extract the table rows
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('#reportsTableBody');
            
            if (newTableBody) {
                // Replace the table body with the new content
                const currentTableBody = document.querySelector('#reportsTableBody');
                if (currentTableBody) {
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }
                
                // Update pagination if it exists
                const newPagination = doc.querySelector('.bg-white.px-4.py-3');
                const currentPagination = document.querySelector('.bg-white.px-4.py-3');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }
            }
        })
        .catch(error => {
            console.error('Error refreshing reports table:', error);
        });
    }
    
    function populatePreviewTable(reportType, data) {
        const container = document.getElementById('previewDataContainer');
        const noDataMessage = document.getElementById('previewNoDataMessage');
        
        if (!data || data.length === 0) {
            container.classList.add('hidden');
            noDataMessage.classList.remove('hidden');
            return;
        }
        
        container.classList.remove('hidden');
        noDataMessage.classList.add('hidden');
        
        let tableHTML = '';
        
        switch (reportType) {
            case 'rainfall_history':
                tableHTML = createRainfallPreviewTableNew(data);
                break;
            case 'message_history':
                tableHTML = createMessagePreviewTableNew(data);
                break;
            case 'device_info':
                tableHTML = createDevicePreviewTableNew(data);
                break;
            case 'contacts_info':
                tableHTML = createContactsPreviewTableNew(data);
                break;
            case 'user_management':
                tableHTML = createUsersPreviewTableNew(data);
                break;
            case 'reports_summary':
                tableHTML = createReportsPreviewTableNew(data);
                break;
            default:
                tableHTML = '<div class="text-center py-8 text-gray-500"><p>Preview not available for this report type</p></div>';
        }
        
        container.innerHTML = tableHTML;
    }
    
    function createRainfallPreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.date}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.location}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.rainfall_mm} mm</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.tips}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.intensity}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} rainfall records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rainfall (mm)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tips</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
    
    function createMessagePreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.date}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.contact}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.location}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.intensity}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.status}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.message}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} message records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
    
    function createDevicePreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.device_id}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.serial_number}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.location}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.status}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.date_installed}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.coordinates}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} device records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Installed</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coordinates</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
    
    function createContactsPreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.name}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.contact_number}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.location}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.position}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.date_added}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} contact records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
    
    function createUsersPreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.username}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.email}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.role}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.full_name}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.date_created}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} user records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
    
    function createReportsPreviewTableNew(data) {
        const rows = data.slice(0, 10).map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${item.report_id}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.report_type}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.requestor}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.organization}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.status}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.date_created}</td>
            </tr>
        `).join('');
        
        return `
            <div class="mb-4">
                <p class="text-sm text-gray-600">Showing ${Math.min(data.length, 10)} of ${data.length} report records</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        `;
    }
});
</script>
@endsection

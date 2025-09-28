@extends('layouts.app')
 
@section('title', 'User Management - MDDRMO Rainfall Monitoring')
@section('page_heading', 'User Management')
 
@section('content')
<style>
/* Enhanced Table Styling */
.min-w-full tbody tr:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

/* Enhanced Modal Backdrop */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
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

/* Form field validation styles */
.form-field-valid {
    border-color: #10b981 !important;
    background-color: #f0fdf4 !important;
}

.form-field-invalid {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}

/* Enhanced Modal */
.modal-container {
    backdrop-filter: blur(4px);
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

.success-message {
    color: #059669;
    font-size: 14px;
    font-weight: 500;
    margin-top: 4px;
    display: block;
    min-height: 0;
    transition: all 0.2s ease;
}

.success-message:empty {
    display: none;
}

/* Error state for form inputs */
.form-input.error,
.form-select.error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.form-input.error:focus,
.form-select.error:focus {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

/* Animation for input field errors */
@keyframes shake {

    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Smooth transitions for error states */
.error-message {
    transition: all 0.2s ease-in-out;
}

.form-input, .form-select {
    transition: all 0.2s ease-in-out;
}

/* Enhanced error banner styling */
#errorBanner {
    transition: all 0.3s ease-in-out;
    animation: slideInFromTop 0.3s ease-out;
}

@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translate(-50%, -30px);
    }
    to {
        opacity: 1;
        transform: translate(-50%, 0);
    }
}

/* Error Banner and Alert Positioning */
.error-banner,
.success-alert {
    position: fixed !important;
    z-index: 99999999 !important;
    pointer-events: auto !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.error-banner {
    top: 1rem !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    max-width: 90vw;
    min-width: 300px;
}

.success-alert {
    top: 1rem !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    max-width: 90vw;
    min-width: 300px;
}

/* Validation Icons */
.validation-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1rem;
    height: 1rem;
    pointer-events: none;
}

.validation-icon.valid {
    color: #10b981;
}

.validation-icon.invalid {
    color: #dc2626;
}
</style>

<div class="relative" id="users-app">
    <div class="container mx-auto px-4 py-6">
        {{-- Success messages will be handled by JavaScript --}}
        @if ($errors->any())
            <div class="mb-4 px-4 py-2 rounded border border-red-300 bg-red-50 text-red-800">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Message container for JavaScript alerts --}}
        <div id="messageContainer" style="display: none;">
            <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>
            <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
        </div>

        <!-- Statistics Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Users</p>
                        <p class="text-2xl font-bold text-green-600">{{ $users->where('status', true)->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Admin Users</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $users->where('role', 'Admin')->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Staff Users</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $users->where('role', 'Staff')->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users-cog text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $users->where('status', false)->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between gap-4">
                <!-- Left Side: Search and Filters -->
                <div class="flex items-center gap-3">
                    <!-- Search Input -->
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
                    
                    <!-- Role Filter Dropdown -->
                    <select id="roleFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-w-[140px]">
                        <option value="all">All Roles</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                    </select>
                    
                    <!-- Status Filter Dropdown -->
                    <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-w-[140px]">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                    </select>
                    
                    <!-- Refresh Button -->
                    <button
                        id="resetFilters"
                        class="w-8 h-8 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center"
                        title="Reset Filters"
                    >
                        <i class="fas fa-redo text-sm"></i>
                    </button>
                </div>
                        
                <!-- Right Side: Action Buttons -->
                <div class="flex items-center gap-3" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm hidden">
                    <button id="showModalButton" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                     <button id="downloadPdfButton" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>

          <!-- Table -->
        <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
                    <div class="px-5 py-3 rounded-t-xl bg-[#242F41] text-white">
                        <h3 class="text-base font-medium flex items-center gap-2">
                            <i class="fas fa-users w-4"></i>
                            Users Information
                        </h3>
                    </div>

           
            <div class="overflow-x-auto">
                <table id="users-table" class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">User ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Username</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Email</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Contact Number</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Role</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 tracking-wider">Action</th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse(($users ?? []) as $user)
                            @php
                                $fullName = trim(($user->first_name ?? '') . ' ' . (($user->middle_name ?? '') ? ($user->middle_name . ' ') : '') . ($user->last_name ?? ''));
                                $displayName = $fullName ?: $user->username;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}" data-user-id="{{ $user->user_id }}">
                                <td class="px-5 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $user->user_id }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $displayName }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->username }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->contact_num ?? '--' }}</td>
                                <td class="px-5 py-3 text-sm whitespace-nowrap">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($user->role == 'Admin') bg-purple-100 text-purple-800
                                        @elseif($user->role == 'Staff') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $user->role ?? 'User' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-sm whitespace-nowrap">
                                    @php $active = (bool) ($user->status ?? true); @endphp
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($active) bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $active ? 'Active' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    <button
                                        data-user-id="{{ $user->user_id }}"
                                        data-user-name="{{ $displayName }}"
                                        data-user-email="{{ $user->email }}"
                                        data-user-role="{{ $user->role ?? '' }}"
                                        class="edit-user-button inline-flex items-center justify-center w-8 h-8 bg-amber-500 text-white rounded-full hover:bg-amber-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2 mr-2 shadow-sm transition-colors"
                                        aria-label="Edit"
                                        title="Edit"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"/><path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"/></svg>
                                    </button>
                                    <button
                                        class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 text-white rounded-full hover:bg-rose-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2 shadow-sm transition-colors"
                                        aria-label="Delete"
                                        title="Delete"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.527 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.951.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5 .058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48 .058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5 .058l.345-9z" clip-rule="evenodd"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            
            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ $users->count() ?? 0 }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Previous</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Next</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination Controls -->
        @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <!-- Mobile Pagination (Previous & Next only) -->
            <div class="flex-1 flex justify-between sm:hidden">
                @if($users->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Next
                    </span>
                @endif
            </div>

            <!-- Desktop Pagination -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-medium">{{ $users->total() }}</span>
                        results
                    </p>
                </div>

                <!-- Numbers + Previous/Next -->
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous Page Link -->
                        @if($users->onFirstPage())
                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md">Previous</a>
                        @endif

                        <!-- Pagination Elements -->
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">Next</a>
                        @else
                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">Next</span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 z-[99999999] flex items-center justify-center bg-black/40 p-4 backdrop-blur-[2px] hidden">
        <div id="createUserModalContent" class="modal-content bg-white rounded-xl w-full max-w-xl relative transform transition-all duration-300 scale-100 shadow-2xl">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Create New User</h2>
                            <p class="text-blue-100 text-sm">Add a new user to the system</p>
                        </div>
                    </div>
                    <button id="closeCreateModalBtn" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="addUserForm" method="POST" action="{{ route('users.store') }}" novalidate>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    First Name
                                    <span class="text-red-500">*</span>
                                </span>
                                <input id="user_first_name" name="first_name" type="text" class="form-input" placeholder="Enter first name" required>
                                <div id="user_first_name_error" class="error-message" style="display: none;"></div>
                                <div id="user_first_name_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    Middle Name
                                </span>
                                <input id="user_middle_name" name="middle_name" type="text" class="form-input" placeholder="Enter middle name (optional)">
                                <div id="user_middle_name_error" class="error-message" style="display: none;"></div>
                                <div id="user_middle_name_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    Last Name
                                </span>
                                <input id="user_last_name" name="last_name" type="text" class="form-input" placeholder="Enter last name" required>
                                <div id="user_last_name_error" class="error-message" style="display: none;"></div>
                                <div id="user_last_name_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                    Email Address
                                </span>
                                <input id="user_email" name="email" type="email" class="form-input" placeholder="Enter email address" required>
                                <div id="user_email_error" class="error-message" style="display: none;"></div>
                                <div id="user_email_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lock text-blue-600"></i>
                                    Password
                                </span>
                                <div class="relative">
                                    <input id="user_password" name="password" type="password" class="form-input pl-3 py-2 pr-10" placeholder="Enter password" minlength="8" required>
                                    <button type="button" id="toggleUserPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                      <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="user_password_error" class="error-message" style="display: none;"></div>
                                <div id="user_password_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    Username
                                </span>
                                <input id="user_username" name="username" type="text" class="form-input" placeholder="Enter username" required>
                                <div id="user_username_error" class="error-message" style="display: none;"></div>
                                <div id="user_username_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-phone text-blue-600"></i>
                                    Contact Number
                                </span>
                                <input id="user_contact_num" name="contact_num" type="text" class="form-input" placeholder="Enter contact number" required>
                                <div id="user_contact_num_error" class="error-message" style="display: none;"></div>
                                <div id="user_contact_num_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-blue-600"></i>
                                    Role
                                </span>
                                <select id="user_role" name="role" class="form-select" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                <div id="user_role_error" class="error-message" style="display: none;"></div>
                                <div id="user_role_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" id="cancelCreateUserBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Save User
                        </button>   
                    </div>  
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 z-[99999999] flex items-center justify-center bg-black/40 p-4 backdrop-blur-[2px] hidden">
        <div id="editUserModalContent" class="modal-content bg-white rounded-xl w-full max-w-2xl relative transform transition-all duration-300 scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-edit text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Edit User</h2>
                            <p class="text-blue-100 text-sm">Update user information</p>
                        </div>
                    </div>
                    <button id="closeEditModalBtn" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    Full Name
                                </span>
                                <input id="edit_user_name" name="name" type="text" class="form-input" placeholder="Enter full name" required>
                                <div id="edit_user_name_error" class="error-message" style="display: none;"></div>
                                <div id="edit_user_name_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                    Email Address
                                </span>
                                <input id="edit_user_email" name="email" type="email" class="form-input" placeholder="Enter email address" required>
                                <div id="edit_user_email_error" class="error-message" style="display: none;"></div>
                                <div id="edit_user_email_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-blue-600"></i>
                                    Role
                                </span>
                                <select id="edit_user_role" name="role" class="form-select">
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                <div id="edit_user_role_error" class="error-message" style="display: none;"></div>
                                <div id="edit_user_role_success" class="success-message" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-toggle-on text-blue-600"></i>
                                    Status
                                </span>
                                <select id="edit_user_status" name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" id="cancelEditUserBtnModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>   
                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update User
                        </button>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Function to show success alert
function showSuccessAlert(message) {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.success-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Create alert element
    const alert = document.createElement('div');
    alert.className = 'success-alert fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-[99999999] transition-all duration-300 opacity-0 translate-y-[-20px]';
    alert.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

    // Add to page
    document.body.appendChild(alert);

    // Animate in
    setTimeout(() => {
        alert.classList.remove('opacity-0', 'translate-y-[-20px]');
        alert.classList.add('opacity-100', 'translate-y-0');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        alert.classList.add('opacity-0', 'translate-y-[-20px]');
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 300);
    }, 5000);
}

// Function to show error banner
function showErrorBanner(message = 'An error occurred. Please try again.') {
    // Remove any existing error banners
    const existingBanner = document.querySelector('.error-banner');
    if (existingBanner) {
        existingBanner.remove();
    }

    // Create error banner element
    const banner = document.createElement('div');
    banner.className = 'error-banner fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-[99999999]';
    banner.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

    // Add to page
    document.body.appendChild(banner);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (banner.parentNode) {
            banner.remove();
        }
    }, 5000);
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

// Function to show field success
function showFieldSuccess(fieldId, message = '') {
    const successElement = document.getElementById(fieldId + '_success');
    if (successElement) {
        successElement.textContent = message;
        successElement.style.display = 'block';
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

// Input sanitization function
function sanitizeInput(input) {
    if (typeof input !== 'string') {
        return '';
    }

    // Remove HTML tags and JavaScript
    let sanitized = input.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
    sanitized = sanitized.replace(/<[^>]*>/g, '');

    // Remove potentially dangerous characters
    sanitized = sanitized.replace(/[<>\"'&]/g, function(match) {
        const entityMap = {
            '<': '<',
            '>': '>',
            '"': '"',
            "'": '&#x27;',
            '&': '&'
        };
        return entityMap[match] || match;
    });

    // Trim whitespace and remove extra spaces
    sanitized = sanitized.trim().replace(/\s+/g, ' ');

    return sanitized;
}

// Function to check if username already exists
function checkUsernameExists(username, excludeUserId = null) {
    return new Promise((resolve) => {
        if (!username || username.trim() === '') {
            resolve(false);
            return;
        }

        fetch('/users/check-username', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                username: username.trim(),
                exclude_user_id: excludeUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            resolve(data.exists || false);
        })
        .catch(() => {
            resolve(false);
        });
    });
}

// Function to check if email already exists
function checkEmailExists(email, excludeUserId = null) {
    return new Promise((resolve) => {
        if (!email || email.trim() === '') {
            resolve(false);
            return;
        }

        fetch('/users/check-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: email.trim(),
                exclude_user_id: excludeUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            resolve(data.exists || false);
        })
        .catch(() => {
            resolve(false);
        });
    });
}

// Function to check if contact number already exists
function checkContactNumExists(contactNum, excludeUserId = null) {
    return new Promise((resolve) => {
        if (!contactNum || contactNum.trim() === '') {
            resolve(false);
            return;
        }

        fetch('/users/check-contact-num', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                contact_num: contactNum.trim(),
                exclude_user_id: excludeUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            resolve(data.exists || false);
        })
        .catch(() => {
            resolve(false);
        });
    });
}

// Function to validate user form (Add User)
function validateUserForm() {
    const errors = {};
    let hasErrors = false;

    // Clear previous errors
    clearFieldError('user_first_name_error');
    clearFieldError('user_last_name_error');
    clearFieldError('user_email_error');
    clearFieldError('user_password_error');
    clearFieldError('user_username_error');
    clearFieldError('user_contact_num_error');
    clearFieldError('user_role_error');

    // Get form elements
    const firstNameInput = document.getElementById('user_first_name');
    const lastNameInput = document.getElementById('user_last_name');
    const emailInput = document.getElementById('user_email');
    const passwordInput = document.getElementById('user_password');
    const usernameInput = document.getElementById('user_username');
    const contactNumInput = document.getElementById('user_contact_num');
    const roleInput = document.getElementById('user_role');

    // First name validation
    const firstName = firstNameInput ? firstNameInput.value.trim() : '';
    if (!firstName) {
        errors.user_first_name_error = 'First name is required.';
        hasErrors = true;
    } else if (firstName.length < 2) {
        errors.user_first_name_error = 'First name must be at least 2 characters long.';
        hasErrors = true;
    } else if (firstName.length > 255) {
        errors.user_first_name_error = 'First name must not exceed 255 characters.';
        hasErrors = true;
    } else if (!/^[A-Za-z\s\-']+$/.test(firstName)) {
        errors.user_first_name_error = 'First name may only contain letters, spaces, hyphens, and apostrophes.';
        hasErrors = true;
    }

    // Last name validation
    const lastName = lastNameInput ? lastNameInput.value.trim() : '';
    if (!lastName) {
        errors.user_last_name_error = 'Last name is required.';
        hasErrors = true;
    } else if (lastName.length < 2) {
        errors.user_last_name_error = 'Last name must be at least 2 characters long.';
        hasErrors = true;
    } else if (lastName.length > 255) {
        errors.user_last_name_error = 'Last name must not exceed 255 characters.';
        hasErrors = true;
    } else if (!/^[A-Za-z\s\-']+$/.test(lastName)) {
        errors.user_last_name_error = 'Last name may only contain letters, spaces, hyphens, and apostrophes.';
        hasErrors = true;
    }

    // Email validation
    const email = emailInput ? emailInput.value.trim() : '';
    if (!email) {
        errors.user_email_error = 'Email is required.';
        hasErrors = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.user_email_error = 'Please enter a valid email address.';
        hasErrors = true;
    }

    // Password validation
    const password = passwordInput ? passwordInput.value : '';
    if (!password) {
        errors.user_password_error = 'Password is required.';
        hasErrors = true;
    } else if (password.length < 8) {
        errors.user_password_error = 'Password must be at least 8 characters long.';
        hasErrors = true;
    } 

    // Username validation
    const username = usernameInput ? usernameInput.value.trim() : '';
    if (!username) {
        errors.user_username_error = 'Username is required.';
        hasErrors = true;
    } else if (username.length < 4) {
        errors.user_username_error = 'Username must be at least 4 characters long.';
        hasErrors = true;
    } else if (username.length > 12) {
        errors.user_username_error = 'Username must not exceed 12 characters.';
        hasErrors = true;
    } 

    // Contact number validation
    const contactNum = contactNumInput ? contactNumInput.value.trim() : '';
    if (!contactNum) {
        errors.user_contact_num_error = 'Contact number is required.';
        hasErrors = true;
    } else if (!/^[0-9+\-\s()]+$/.test(contactNum)) {
        errors.user_contact_num_error = 'Contact number format is invalid.';
        hasErrors = true;
    } else if (contactNum.length < 10) {
        errors.user_contact_num_error = 'Contact number must be at least 10 characters.';
        hasErrors = true;
    } else if (contactNum.length > 15) {
        errors.user_contact_num_error = 'Contact number must not exceed 15 characters.';
        hasErrors = true;
    }

    // Role validation
    const role = roleInput ? roleInput.value : '';
    if (!role) {
        errors.user_role_error = 'Role is required.';
        hasErrors = true;
    }

    // Middle name validation (optional but if provided should be valid)
    const middleNameInput = document.getElementById('user_middle_name');
    const middleName = middleNameInput ? middleNameInput.value.trim() : '';
    if (middleName && !/^[A-Za-z\s\-']+$/.test(middleName)) {
        errors.user_middle_name_error = 'Middle name may only contain letters, spaces, hyphens, and apostrophes.';
        hasErrors = true;
    }

    return { errors, hasErrors };
}

document.addEventListener('DOMContentLoaded', function() {
    const addUserForm = document.getElementById('addUserForm');
    const createUserModal = document.getElementById('createUserModal');
    const editUserModal = document.getElementById('editUserModal');
    const showModalButton = document.getElementById('showModalButton');
    const closeCreateModalBtn = document.getElementById('closeCreateModalBtn');
    const cancelCreateUserBtn = document.getElementById('cancelCreateUserBtn');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const cancelEditUserBtnModal = document.getElementById('cancelEditUserBtnModal');
    
    // Modal functions for consistency with index.blade.php
    function openCreateUserModal() {
        if (createUserModal) {
            createUserModal.classList.remove('hidden');
            clearAllFormErrors('addUserForm');
        }
    }

    function closeCreateUserModalFunc() {
        if (createUserModal) {
            createUserModal.classList.add('hidden');
        }
    }
    
    function cancelCreateUserModalFunc() {
        if (createUserModal) {
            createUserModal.classList.add('hidden');
            
            if (addUserForm) {
                addUserForm.reset();
                clearAllFormErrors('addUserForm');
            }
        }
    }

    function openEditUserModal() {
        if (editUserModal) {
            editUserModal.classList.remove('hidden');
        }
    }

    function closeEditUserModalFunc() {
        if (editUserModal) {
            editUserModal.classList.add('hidden');
        }
    }
    
    function cancelEditUserModalFunc() {
        if (editUserModal) {
            editUserModal.classList.add('hidden');
        }
    }

    // Event listeners for modal functions
    if (showModalButton) {
        showModalButton.addEventListener('click', openCreateUserModal);
    }
    
    if (closeCreateModalBtn) {
        closeCreateModalBtn.addEventListener('click', closeCreateUserModalFunc);
    }
    
    if (cancelCreateUserBtn) {
        cancelCreateUserBtn.addEventListener('click', cancelCreateUserModalFunc);
    }
    
    if (closeEditModalBtn) {
        closeEditModalBtn.addEventListener('click', closeEditUserModalFunc);
    }
    
    if (cancelEditUserBtnModal) {
        cancelEditUserBtnModal.addEventListener('click', cancelEditUserModalFunc);
    }
    
    // Close modals when clicking outside
    if (createUserModal) {
        createUserModal.addEventListener('click', function(e) {
            if (e.target === createUserModal) {
                closeCreateUserModalFunc();
            }
        });
    }
    
    if (editUserModal) {
        editUserModal.addEventListener('click', function(e) {
            if (e.target === editUserModal) {
                closeEditUserModalFunc();
            }
        });
    }
    
    // Prevent modal from closing when clicking inside
    if (createUserModal && createUserModal.querySelector('.modal-content')) {
        createUserModal.querySelector('.modal-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (editUserModal && editUserModal.querySelector('.modal-content')) {
        editUserModal.querySelector('.modal-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Handle edit user button clicks
    document.querySelectorAll('.edit-user-button').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const userEmail = this.getAttribute('data-user-email');
            const userRole = this.getAttribute('data-user-role');
            
            showEditUserModal(userId, userName, userEmail, userRole);
        });
    });
    
    // Show edit user modal function
    function showEditUserModal(userId, userName, userEmail, userRole) {
        const editUserId = document.getElementById('edit_user_id');
        const editUserName = document.getElementById('edit_user_name');
        const editUserEmail = document.getElementById('edit_user_email');
        const editUserRole = document.getElementById('edit_user_role');
        const editUserStatus = document.getElementById('edit_user_status');
        const editUserForm = document.getElementById('editUserForm');
        
        if (editUserId) editUserId.value = userId;
        if (editUserName) editUserName.value = userName;
        if (editUserEmail) editUserEmail.value = userEmail;
        if (editUserRole) editUserRole.value = userRole;
        
        // Set default status if not provided
        if (editUserStatus) {
            editUserStatus.value = 'active'; // Default to active
        }
        
        // Update form action
        if (editUserForm) {
            editUserForm.action = `/users/${userId}`;
        }
        
        if (editUserModal) {
            editUserModal.classList.remove('hidden');
        }
    }
    
    // Add performSearch function
    window.performSearch = function() {
        // This would typically trigger a form submission or AJAX call
        // For now, we'll just reload the page with search parameters
        const searchTerm = document.getElementById('searchTerm') ? document.getElementById('searchTerm').value : '';
        const roleFilter = document.getElementById('roleFilter') ? document.getElementById('roleFilter').value : 'all';
        const statusFilter = document.getElementById('statusFilter') ? document.getElementById('statusFilter').value : 'all';
        
        let url = window.location.href.split('?')[0];
        const params = new URLSearchParams();
        
        if (searchTerm) params.append('search', searchTerm);
        if (roleFilter !== 'all') params.append('role', roleFilter);
        if (statusFilter !== 'all') params.append('status', statusFilter);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    };
    
    // Add event listeners for search functionality
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            window.performSearch();
        });
    }
    
    const searchTermInput = document.getElementById('searchTerm');
    if (searchTermInput) {
        searchTermInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.performSearch();
            }
        });
    }
    
    // Add event listeners for filter changes
    const roleFilter = document.getElementById('roleFilter');
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            window.performSearch();
        });
    }
    
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            window.performSearch();
        });
    }
    
    // Reset filters button
    const resetFiltersButton = document.getElementById('resetFilters');
    if (resetFiltersButton) {
        resetFiltersButton.addEventListener('click', function() {
            if (searchTermInput) searchTermInput.value = '';
            if (roleFilter) roleFilter.value = 'all';
            if (statusFilter) statusFilter.value = 'all';
            window.performSearch();
        });
    }
    
    // Real-time validation for add user form
    const firstNameInput = document.getElementById('user_first_name');
    const middleNameInput = document.getElementById('user_middle_name');
    const lastNameInput = document.getElementById('user_last_name');
    const emailInput = document.getElementById('user_email');
    const passwordInput = document.getElementById('user_password');
    const usernameInput = document.getElementById('user_username');
    const contactNumInput = document.getElementById('user_contact_num');
    const roleInput = document.getElementById('user_role');

    // Add real-time validation as user types
    if (firstNameInput) {
        firstNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_first_name_error');
            clearFieldSuccess('user_first_name');
            
            // Real-time validation
            if (!value) {
                showFieldError('user_first_name_error', 'First name is required.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 2) {
                showFieldError('user_first_name_error', 'First name must be at least 2 characters long.');
                this.classList.add('form-field-invalid');
            } else if (value.length > 255) {
                showFieldError('user_first_name_error', 'First name must not exceed 255 characters.');
                this.classList.add('form-field-invalid');
            } else if (!/^[A-Za-z\s\-']+$/.test(value)) {
                showFieldError('user_first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('user_first_name_error');
                showFieldSuccess('user_first_name');
                this.classList.remove('form-field-invalid');
            }
        });
        
        // Add blur event to check for empty field
        firstNameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('user_first_name_error', 'First name is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (middleNameInput) {
        middleNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_middle_name_error');
            clearFieldSuccess('user_middle_name');
            
            // Real-time validation
            if (value && !/^[A-Za-z\s\-']+$/.test(value)) {
                showFieldError('user_middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
            } else if (value) {
                clearFieldError('user_middle_name_error');
                showFieldSuccess('user_middle_name');
            } else {
                // Clear validation for empty optional field
                clearFieldError('user_middle_name_error');
                clearFieldSuccess('user_middle_name');
            }
        });
    }

    if (lastNameInput) {
        lastNameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_last_name_error');
            clearFieldSuccess('user_last_name');
            
            // Real-time validation
            if (!value) {
                showFieldError('user_last_name_error', 'Last name is required.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 2) {
                showFieldError('user_last_name_error', 'Last name must be at least 2 characters long.');
                this.classList.add('form-field-invalid');
            } else if (value.length > 255) {
                showFieldError('user_last_name_error', 'Last name must not exceed 255 characters.');
                this.classList.add('form-field-invalid');
            } else if (!/^[A-Za-z\s\-']+$/.test(value)) {
                showFieldError('user_last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('user_last_name_error');
                showFieldSuccess('user_last_name');
                this.classList.remove('form-field-invalid');
            }
        });
        
        // Add blur event to check for empty field
        lastNameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('user_last_name_error', 'Last name is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (emailInput) {
        let emailCheckTimeout;
        emailInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_email_error');
            clearFieldSuccess('user_email');
            
            // Clear previous timeout
            if (emailCheckTimeout) {
                clearTimeout(emailCheckTimeout);
            }
            
            // Real-time validation
            if (!value) {
                showFieldError('user_email_error', 'Email is required.');
                this.classList.add('form-field-invalid');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                showFieldError('user_email_error', 'Please enter a valid email address.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success and check for duplicates
                clearFieldError('user_email_error');
                showFieldSuccess('user_email');
                this.classList.remove('form-field-invalid');
                
                // Check for duplicate email after user stops typing (debounced)
                emailCheckTimeout = setTimeout(async () => {
                    const emailExists = await checkEmailExists(value);
                    if (emailExists) {
                        showFieldError('user_email_error', 'This email is already in use.');
                        clearFieldSuccess('user_email');
                    } else {
                        showFieldSuccess('user_email');
                    }
                }, 500); // 500ms delay
            }
        });
        
        // Add blur event to check for empty field
        emailInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('user_email_error', 'Email is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            clearFieldError('user_password_error');
            clearFieldSuccess('user_password');
            
            // Real-time validation
            if (!value) {
                showFieldError('user_password_error', 'Password is required.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 8) {
                showFieldError('user_password_error', 'Password must be at least 8 characters long.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('user_password_error');
                showFieldSuccess('user_password');
                this.classList.remove('form-field-invalid');
            }
        });
        
        // Add blur event to check for empty field
        passwordInput.addEventListener('blur', function() {
            if (!this.value) {
                showFieldError('user_password_error', 'Password is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (usernameInput) {
        let usernameCheckTimeout;
        usernameInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_username_error');
            clearFieldSuccess('user_username');
            
            // Clear previous timeout
            if (usernameCheckTimeout) {
                clearTimeout(usernameCheckTimeout);
            }
            
            // Real-time validation
            if (!value) {
                showFieldError('user_username_error', 'Username is required.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 4) {
                showFieldError('user_username_error', 'Username must be at least 4 characters long.');
                this.classList.add('form-field-invalid');
            } else if (value.length > 12) {
                showFieldError('user_username_error', 'Username must not exceed 12 characters.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success and check for duplicates
                clearFieldError('user_username_error');
                showFieldSuccess('user_username');
                this.classList.remove('form-field-invalid');
                
                // Check for duplicate username after user stops typing (debounced)
                usernameCheckTimeout = setTimeout(async () => {
                    const usernameExists = await checkUsernameExists(value);
                    if (usernameExists) {
                        showFieldError('user_username_error', 'This username is already in use.');
                        clearFieldSuccess('user_username');
                    } else {
                        showFieldSuccess('user_username');
                    }
                }, 500); // 500ms delay
            }
        });
        
        // Add blur event to check for empty field
        usernameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('user_username_error', 'Username is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (contactNumInput) {
        let contactNumCheckTimeout;
        contactNumInput.addEventListener('input', function() {
            const value = this.value.trim();
            clearFieldError('user_contact_num_error');
            clearFieldSuccess('user_contact_num');
            
            // Clear previous timeout
            if (contactNumCheckTimeout) {
                clearTimeout(contactNumCheckTimeout);
            }
            
            // Real-time validation
            if (!value) {
                showFieldError('user_contact_num_error', 'Contact number is required.');
                this.classList.add('form-field-invalid');
            } else if (!/^[0-9+\-\s()]+$/.test(value)) {
                showFieldError('user_contact_num_error', 'Contact number format is invalid.');
                this.classList.add('form-field-invalid');
            } else if (value.length < 11) {
                showFieldError('user_contact_num_error', 'Contact number must be at least 11 characters.');
                this.classList.add('form-field-invalid');
            } else if (value.length > 11) {
                showFieldError('user_contact_num_error', 'Contact number must not exceed 11 characters.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('user_contact_num_error');
                showFieldSuccess('user_contact_num');
                this.classList.remove('form-field-invalid');

                // Check for duplicate contact number after user stops typing (debounced)
                contactNumCheckTimeout = setTimeout(async () => {
                    const contactNumExists = await checkContactNumExists(value);
                    if (contactNumExists) {
                        showFieldError('user_contact_num_error', 'This contact number is already in use.');
                        clearFieldSuccess('user_contact_num');
                    } else {
                        showFieldSuccess('user_contact_num');
                    }
                }, 500); // 500ms delay
            }
        });
        
        // Add blur event to check for empty field
        contactNumInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError('user_contact_num_error', 'Contact number is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    if (roleInput) {
        roleInput.addEventListener('change', function() {
            const value = this.value;
            clearFieldError('user_role_error');
            clearFieldSuccess('user_role');
            
            // Real-time validation
            if (!value) {
                showFieldError('user_role_error', 'Role is required.');
                this.classList.add('form-field-invalid');
            } else {
                // Valid format - show success
                clearFieldError('user_role_error');
                showFieldSuccess('user_role');
                this.classList.remove('form-field-invalid');
            }
        });
        
        // Add blur event to check for empty field
        roleInput.addEventListener('blur', function() {
            if (!this.value) {
                showFieldError('user_role_error', 'Role is required.');
                this.classList.add('form-field-invalid');
            }
        });
    }

    // Form submission
    if (addUserForm) {
        addUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Prevent multiple submissions
            const submitButton = addUserForm.querySelector('button[type="submit"]');
            const originalBtnText = submitButton ? submitButton.innerHTML : 'Save User';

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            }

            // Clear all previous errors
            clearFieldError('user_first_name_error');
            clearFieldError('user_middle_name_error');
            clearFieldError('user_last_name_error');
            clearFieldError('user_email_error');
            clearFieldError('user_password_error');
            clearFieldError('user_username_error');
            clearFieldError('user_contact_num_error');
            clearFieldError('user_role_error');

            // Client-side validation
            const validation = validateUserForm();
            
            if (validation.hasErrors) {
                // Show error banner
                showErrorBanner('Please fix the validation errors before submitting.');

                // Display validation errors
                Object.keys(validation.errors).forEach(fieldId => {
                    showFieldError(fieldId, validation.errors[fieldId]);
                });

                // Re-enable submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalBtnText;
                }

                return;
            }

            // Sanitize inputs
            const firstNameInput = document.getElementById('user_first_name');
            const middleNameInput = document.getElementById('user_middle_name');
            const lastNameInput = document.getElementById('user_last_name');
            const emailInput = document.getElementById('user_email');
            const passwordInput = document.getElementById('user_password');
            const usernameInput = document.getElementById('user_username');
            const contactNumInput = document.getElementById('user_contact_num');
            const roleInput = document.getElementById('user_role');

            if (firstNameInput) firstNameInput.value = sanitizeInput(firstNameInput.value);
            if (middleNameInput) middleNameInput.value = sanitizeInput(middleNameInput.value);
            if (lastNameInput) lastNameInput.value = sanitizeInput(lastNameInput.value);
            if (emailInput) emailInput.value = sanitizeInput(emailInput.value);
            if (passwordInput) passwordInput.value = sanitizeInput(passwordInput.value);
            if (usernameInput) usernameInput.value = sanitizeInput(usernameInput.value);
            if (contactNumInput) contactNumInput.value = sanitizeInput(contactNumInput.value);
            if (roleInput) roleInput.value = sanitizeInput(roleInput.value);

            // If validation passes, submit the form
            const formData = new FormData(addUserForm);
            
            try {
                const response = await fetch(addUserForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(JSON.stringify(errorData));
                }

                const data = await response.json();
                
                if (data.success) {
                    // Success - close modal and show success message
                    if (createUserModal) {
                        createUserModal.classList.add('hidden');
                    }
                    if (addUserForm) {
                        addUserForm.reset();
                        clearAllFormErrors('addUserForm');
                    }
                    
                    // Show success message
                    showSuccessAlert('User added successfully.');
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Display server-side validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(fieldName => {
                            const fieldId = 'user_' + fieldName + '_error';
                            showFieldError(fieldId, data.errors[fieldName][0]);
                        });
                        showErrorBanner('Please fix the validation errors before submitting.');
                    } else {
                        showErrorBanner(data.message || 'Failed to create user');
                    }
                }
            } catch (error) {
                try {
                    const errorData = JSON.parse(error.message);
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(fieldName => {
                            const fieldId = 'user_' + fieldName + '_error';
                            showFieldError(fieldId, errorData.errors[fieldName][0]);
                        });
                        showErrorBanner('Please fix the validation errors before submitting.');
                    } else {
                        showErrorBanner(errorData.message || 'Failed to create user');
                    }
                } catch (e) {
                    console.error('Error:', error);
                    showErrorBanner('An error occurred while creating the user.');
                }
            } finally {
                // Restore button state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalBtnText;
                }
            }
        });
    }

    // Handle edit user form submission
    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        // Add real-time validation for edit user form fields
        const editUserNameInput = document.getElementById('edit_user_name');
        const editUserEmailInput = document.getElementById('edit_user_email');
        const editUserRoleInput = document.getElementById('edit_user_role');
        const editUserStatusInput = document.getElementById('edit_user_status');
        
        // Add real-time validation for name field (edit form)
        if (editUserNameInput) {
            editUserNameInput.addEventListener('input', function() {
                const value = this.value.trim();
                clearFieldError('edit_user_name_error');
                clearFieldSuccess('edit_user_name');
                
                // Real-time validation
                if (!value) {
                    showFieldError('edit_user_name_error', 'Name is required.');
                    this.classList.add('form-field-invalid');
                } else if (value.length < 2) {
                    showFieldError('edit_user_name_error', 'Name must be at least 2 characters long.');
                    this.classList.add('form-field-invalid');
                } else if (value.length > 255) {
                    showFieldError('edit_user_name_error', 'Name must not exceed 255 characters.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Valid format - clear error
                    clearFieldError('edit_user_name_error');
                    showFieldSuccess('edit_user_name');
                    this.classList.remove('form-field-invalid');
                }
            });
            
            // Add blur event to check for empty field
            editUserNameInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    showFieldError('edit_user_name_error', 'Name is required.');
                    this.classList.add('form-field-invalid');
                }
            });
        }
        
        // Add real-time validation for email field (edit form)
        if (editUserEmailInput) {
            let editEmailCheckTimeout;
            editUserEmailInput.addEventListener('input', function() {
                const value = this.value.trim();
                clearFieldError('edit_user_email_error');
                clearFieldSuccess('edit_user_email');
                
                // Clear previous timeout
                if (editEmailCheckTimeout) {
                    clearTimeout(editEmailCheckTimeout);
                }
                
                // Real-time validation
                if (!value) {
                    showFieldError('edit_user_email_error', 'Email is required.');
                    this.classList.add('form-field-invalid');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    showFieldError('edit_user_email_error', 'Please enter a valid email address.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Valid format - clear error
                    clearFieldError('edit_user_email_error');
                    showFieldSuccess('edit_user_email', 'Valid email format');
                    this.classList.remove('form-field-invalid');
                    
                    // Check for duplicate email after user stops typing (debounced)
                    editEmailCheckTimeout = setTimeout(async () => {
                        // Get the current user ID to exclude from duplicate check
                        const userId = document.getElementById('edit_user_id')?.value;
                        const emailExists = await checkEmailExists(value, userId);
                        if (emailExists) {
                            showFieldError('edit_user_email_error', 'This email is already in use.');
                            clearFieldSuccess('edit_user_email');
                        } else {
                            showFieldSuccess('edit_user_email');
                        }
                    }, 500); // 500ms delay
                }
            });
            
            // Add blur event to check for empty field
            editUserEmailInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    showFieldError('edit_user_email_error', 'Email is required.');
                    this.classList.add('form-field-invalid');
                }
            });
        }
        
        // Add real-time validation for role field (edit form)
        if (editUserRoleInput) {
            editUserRoleInput.addEventListener('change', function() {
                const value = this.value;
                clearFieldError('edit_user_role_error');
                clearFieldSuccess('edit_user_role');
                
                // Real-time validation
                if (!value) {
                    showFieldError('edit_user_role_error', 'Role is required.');
                    this.classList.add('form-field-invalid');
                } else {
                    // Valid format - clear error
                    clearFieldError('edit_user_role_error');
                    showFieldSuccess('edit_user_role', 'Role selected');
                    this.classList.remove('form-field-invalid');
                }
            });
            
            // Add blur event to check for empty field
            editUserRoleInput.addEventListener('blur', function() {
                if (!this.value) {
                    showFieldError('edit_user_role_error', 'Role is required.');
                    this.classList.add('form-field-invalid');
                }
            });
        }
        
        editUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Prevent multiple submissions
            const submitButton = editUserForm.querySelector('button[type="submit"]');
            const originalBtnText = submitButton ? submitButton.innerHTML : 'Update User';

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
            }

            try {
                const formData = new FormData(editUserForm);
                const response = await fetch(editUserForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(JSON.stringify(errorData));
                }

                const data = await response.json();
                
                if (data.success) {
                    // Success - close modal and show success message
                    if (editUserModal) {
                        editUserModal.classList.add('hidden');
                    }
                    
                    // Show success message
                    showSuccessAlert('User updated successfully.');
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Display server-side validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(fieldName => {
                            const errorId = 'edit_user_' + fieldName + '_error';
                            showFieldError(errorId, data.errors[fieldName][0]);
                        });
                    } else {
                        showErrorBanner(data.message || 'Failed to update user');
                    }
                }
            } catch (error) {
                try {
                    const errorData = JSON.parse(error.message);
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(fieldName => {
                            const errorId = 'edit_user_' + fieldName + '_error';
                            showFieldError(errorId, errorData.errors[fieldName][0]);
                        });
                    } else {
                        showErrorBanner(errorData.message || 'Failed to update user');
                    }
                } catch (e) {
                    console.error('Error:', error);
                    showErrorBanner('An error occurred while updating the user.');
                }
            } finally {
                // Restore button state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalBtnText;
                }
            }
        });
    }

    // Function to clear all form errors
    function clearAllFormErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        // Clear error messages
        const errorElements = form.querySelectorAll('[id$="_error"]');
        errorElements.forEach(element => {
            element.style.display = 'none';
            element.textContent = '';
        });
        
        // Clear success messages
        const successElements = form.querySelectorAll('[id$="_success"]');
        successElements.forEach(element => {
            element.style.display = 'none';
            element.textContent = '';
        });
        
        // Clear form field styling
        const formFields = form.querySelectorAll('.form-input, .form-select');
        formFields.forEach(field => {
            field.classList.remove('form-field-invalid', 'form-field-valid');
        });
    }
    
    // Toggle password visibility for user creation
    const toggleUserPassword = document.getElementById('toggleUserPassword');
    const userPasswordInput = document.getElementById('user_password');
    
    if (toggleUserPassword && userPasswordInput) {
      toggleUserPassword.addEventListener('click', function() {
        const type = userPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        userPasswordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      });
    }
    
  
});
</script>
@endsection
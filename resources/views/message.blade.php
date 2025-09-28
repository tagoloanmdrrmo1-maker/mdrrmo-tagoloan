{{-- resources/views/messages.blade.php --}}
@extends('layouts.app')

@section('page_heading', 'Message Management')
@section('title', 'Message Management')

@section('content')
<style>
/* Keeping all existing styles unchanged */
.min-w-full tbody tr:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.btn-primary {
    background: linear-gradient(135deg, #185ea6 0%, #134d8a 100%);
    box-shadow: 0 2px 4px rgba(24, 94, 166, 0.2);
    transition: all 0.2s ease;
}

.btn-primary:hover {
    box-shadow: 0 4px 8px rgba(24, 94, 166, 0.3);
    transform: translateY(-1px);
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
    min-height: 120px;
    resize: vertical;
}

.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-color: #ffffff;
}

/* Error message styling */
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

/* Success message styling */
.success-message {
    color: #059669;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Success banner styling */
.success-banner {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    /* Add positioning to match other blade files */
    position: fixed !important;
    top: 1rem !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    max-width: 90vw;
    min-width: 300px;
    z-index: 9999999 !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Error state for form inputs */
.form-input.error,
.form-select.error,
.form-textarea.error,
.form-field-invalid {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.form-input.error:focus,
.form-select.error:focus,
.form-textarea.error:focus,
.form-field-invalid:focus {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

/* Success state for form inputs */
.form-field-valid {
    border-color: #10b981 !important;
    background-color: #f0fdf4 !important;
}

/* Error banner styling */
.error-banner {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

/* Intensity Level Badges */
.intensity-critical {
    background-color: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.intensity-high {
    background-color: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.intensity-moderate {
    background-color: #f9fafb;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

/* Status Badges */
.status-sent {
    background-color: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.status-delivered {
    background-color: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.status-pending {
    background-color: #fffbeb;
    color: #d97706;
    border: 1px solid #fed7aa;
}

/* Composed by badge */
.composed-by-badge {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

/* Text description cell styling */
.text-description-cell {
    max-width: 200px;
}

.text-description-content {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<div x-data="{ 
    selected: 'all', 
    sendMode: 'all', 
    selectedArea: '', 
    contactType: [],
    deliveryMethod: 'system', 
    messageContent: '',
    searchTerm: '',
    statusFilter: 'all',
    levelFilter: 'all',
    resetFilters() {
        this.searchTerm = '';
        this.statusFilter = 'all';
        this.levelFilter = 'all';
    }
}" class="relative">
    <div class="container mx-auto px-4 py-6">
        <!-- Statistics Cards Section - Updated to use cleaner data access -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Messages</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $totalResults = method_exists($messages, 'total') ? $messages->total() : $messages->count();
                            @endphp
                            {{ $totalResults }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Sent Messages</p>
                        <p class="text-2xl font-bold text-green-600">
                            @php
                                // We'll need to get this from the paginated results
                                $sentCount = method_exists($messages, 'total') ? 
                                    $messages->where('status', 'Sent')->count() : 
                                    $messages->where('status', 'Sent')->count();
                            @endphp
                            {{ $sentCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Messages</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            @php
                                // We'll need to get this from the paginated results
                                $pendingCount = method_exists($messages, 'total') ? 
                                    $messages->where('status', 'Pending')->count() : 
                                    $messages->where('status', 'Pending')->count();
                            @endphp
                            {{ $pendingCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section - Simplified -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between gap-4">
                <!-- Search Section -->
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('messages.index') }}" class="flex items-center">
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
                    
                    <!-- Filters -->
                    <div class="flex items-center gap-3">
                        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>

                        <select name="level" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="this.form.submit()">
                            <option value="all" {{ request('level') == 'all' ? 'selected' : '' }}>All Levels</option>
                            <option value="Critical" {{ request('level') == 'Critical' ? 'selected' : '' }}>Critical</option>
                            <option value="High" {{ request('level') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Moderate" {{ request('level') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="Light" {{ request('level') == 'Light' ? 'selected' : '' }}>Light</option>
                        </select>

                        <button
                            type="submit"
                            name="reset"
                            value="1"
                            class="w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 flex items-center justify-center"
                            title="Reset Filters"
                        >
                            <i class="fas fa-redo text-sm"></i>
                        </button>
                    </div>
                </div>
                        
                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button onclick="openComposeMessageModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors flex items-center gap-2">
                        <i class="fas fa-envelope"></i> Compose Message
                    </button>
                    <button 
                        id="downloadMessagesBtn"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2"
                    >
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Messages Table - Updated with cleaner data structure -->
      <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
            <div class="px-5 py-3 rounded-t-xl bg-[#242F41] text-white">
                <h3 class="text-base font-medium flex items-center gap-2">
                    <i class="fas fa-bell w-4"></i>
                    Alert Messages
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Message ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Contact Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Intensity Level</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Brgy Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Contact Number</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Text Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Composed by</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Date Created</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($messages as $data)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $data->mes_id ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @php
                                        $contactName = 'N/A';
                                        if ($data->contact) {
                                            if (!empty($data->contact->firstname) || !empty($data->contact->lastname)) {
                                                $contactName = trim(($data->contact->firstname ?? '') . ' ' . ($data->contact->middlename ?? '') . ' ' . ($data->contact->lastname ?? ''));
                                                $contactName = $contactName ?: 'N/A';
                                            }
                                        }
                                    @endphp
                                    {{ $contactName }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        // Normalize legacy labels to new ones
                                        $rawIntensity = $data->intensity_level ?? 'N/A';
                                        $intensity = match($rawIntensity) {
                                            'Critical' => 'Torrential',
                                            'High' => 'Intense',
                                            default => $rawIntensity
                                        };
                                        $intensityClass = match($intensity) {
                                            'Torrential' => 'intensity-critical',
                                            'Intense' => 'intensity-high',
                                            default => 'intensity-moderate'
                                        };
                                        $intensityIcon = match($intensity) {
                                            'Torrential' => 'fas fa-exclamation-triangle',
                                            'Intense' => 'fas fa-exclamation-circle',
                                            'Moderate' => 'fas fa-info-circle',
                                            'Light' => 'fas fa-info-circle',
                                            default => 'fas fa-info-circle'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $intensityClass }}">
                                        {{ $intensity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $data->contact->brgy_location ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $data->contact->contact_num ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-description-cell">
                                    <div class="text-description-content">
                                        {{ $data->text_desc ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @php
                                        // Get composed by information - get user information directly from the users table
                                        $composedBy = null;
                                        if (isset($data->user) && $data->user) {
                                            // Get the full name from user fields directly
                                            if (!empty($data->user->first_name) || !empty($data->user->last_name)) {
                                                $composedBy = trim(($data->user->first_name ?? '') . ' ' . ($data->user->middle_name ?? '') . ' ' . ($data->user->last_name ?? ''));
                                            }
                                            // Fallback to username if no name fields
                                            $composedBy = $composedBy ?: $data->user->username;
                                        } elseif (isset($data->created_by)) {
                                            $composedBy = $data->created_by;
                                        }
                                    @endphp
                                    @if($composedBy)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium composed-by-badge">
                                            {{ ucfirst($composedBy) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Not specified</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $data->date_created ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $status = $data->status ?? 'N/A';
                                        $statusClass = match($status) {
                                            'Sent' => 'status-sent',
                                            'Delivered' => 'status-delivered',
                                            'Pending' => 'status-pending',
                                            default => 'status-sent'
                                        };
                                        $statusIcon = match($status) {
                                            'Sent' => 'fas fa-check',
                                            'Delivered' => 'fas fa-check-double',
                                            'Pending' => 'fas fa-clock',
                                            default => 'fas fa-check'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">No messages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Results summary and pagination controls -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div>
                    @php
                        $isPaginated = method_exists($messages, 'firstItem');
                        $totalResults = $isPaginated && method_exists($messages, 'total')
                            ? $messages->total()
                            : (is_countable($messages) ? count($messages) : 0);
                        $firstItem = $isPaginated ? ($messages->firstItem() ?? 0) : ($totalResults > 0 ? 1 : 0);
                        $lastItem  = $isPaginated ? ($messages->lastItem() ?? 0)  : $totalResults;
                    @endphp
                   
                </div>
            </div>
            
            <!-- Pagination Controls -->
            @if($isPaginated && $messages->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <!-- Mobile Pagination (Previous & Next only) -->
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($messages->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $messages->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    @endif

                    @if($messages->hasMorePages())
                        <a href="{{ $messages->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            >
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
                    </div>

                    <!-- Numbers + Previous/Next -->
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <!-- Previous Page Link -->
                            @if($messages->onFirstPage())
                                <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed"><</span>
                            @else
                                <a href="{{ $messages->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md"><</a>
                            @endif

                            <!-- Pagination Elements -->
                            @foreach ($messages->getUrlRange(1, $messages->lastPage()) as $page => $url)
                                @if ($page == $messages->currentPage())
                                    <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                                @endif
                            @endforeach

                            <!-- Next Page Link -->
                            @if($messages->hasMorePages())
                                <a href="{{ $messages->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">></a>
                            @else
                                <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">></span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        </div>
        


    </div>

<script>
    // Unified banner functions
    function showMessageErrorBanner(message = 'Please fix the validation errors before submitting.') {
        if (typeof window.showBanner === 'function') {
            window.showBanner('error', message);
        } else {
            showCustomBanner('error-banner', message, 'bg-red-100 border-red-400 text-red-700', 'fas fa-exclamation-triangle', 5000);
        }
    }

    function showMessageSuccessBanner(message = 'Message sent successfully!') {
        showCustomBanner('success-banner', message, 'bg-green-100 border-green-400 text-green-700', 'fas fa-check-circle', 1500);
    }

    function showCustomBanner(className, message, colorClasses, iconClass, duration) {
        // Remove existing banner of same type
        const existing = document.querySelector(`.${className}`);
        if (existing) existing.remove();

        const banner = document.createElement('div');
        banner.className = `${className} fixed top-4 left-1/2 transform -translate-x-1/2 ${colorClasses} px-6 py-4 rounded-lg shadow-lg z-[9999999]`;
        banner.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="${iconClass} text-lg"></i>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(banner);
        setTimeout(() => banner.remove(), duration);
    }

    // Field validation functions
    function toggleFieldError(errorId, message, show = true) {
        const errorElement = document.getElementById(errorId);
        const fieldId = errorId.replace('_error', '');
        const field = document.getElementById(fieldId);

        if (errorElement) {
            if (show && message) {
                const displayMessage = Array.isArray(message) ? message[0] : message;
                errorElement.textContent = displayMessage;
                errorElement.style.display = 'block';
                errorElement.classList.remove('hidden');
                errorElement.style.visibility = 'visible';
                errorElement.style.opacity = '1';
            } else {
                errorElement.style.display = 'none';
                errorElement.classList.add('hidden');
                errorElement.style.visibility = 'hidden';
                errorElement.style.opacity = '0';
                errorElement.textContent = '';
            }
        }

        if (field) {
            field.classList.toggle('form-field-invalid', show);
            field.classList.toggle('form-field-valid', !show && message);
        }
    }

    function showMessageFieldError(errorId, message) {
        toggleFieldError(errorId, message, true);
    }

    function clearMessageFieldError(errorId) {
        toggleFieldError(errorId, null, false);
    }

    function showMessageFieldSuccess(fieldId, message = '') {
        const successElement = document.getElementById(fieldId + '_success');
        const field = document.getElementById(fieldId);

        if (successElement) {
            successElement.textContent = message;
            successElement.style.display = 'flex';
        }

        if (field) {
            field.classList.remove('form-field-invalid');
            field.classList.add('form-field-valid');
        }
    }

    function clearMessageFieldSuccess(fieldId) {
        const successElement = document.getElementById(fieldId + '_success');
        const field = document.getElementById(fieldId);

        if (successElement) {
            successElement.style.display = 'none';
            successElement.textContent = '';
        }

        if (field) {
            field.classList.remove('form-field-valid');
        }
    }

    // Validation setup functions
    function setupFieldValidation(fieldId, validationFn, events = ['input', 'blur']) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        events.forEach(event => {
            field.addEventListener(event, () => {
                clearMessageFieldError(fieldId + '_error');
                clearMessageFieldSuccess(fieldId);
                validationFn(field);
            });
        });
    }

    function setupMessageFormValidation() {
        // Send mode validation
        document.querySelectorAll('input[name="send_mode"]').forEach(radio => {
            radio.addEventListener('change', () => {
                clearMessageFieldError('send_mode_error');
                showMessageFieldSuccess('send_mode');
            });
        });

        // Selected area validation
        setupFieldValidation('selected_area', (field) => {
            const value = field.value.trim();
            if (!value) {
                showMessageFieldError('selected_area_error', 'Area selection is required for single mode.');
            } else {
                showMessageFieldSuccess('selected_area');
                updateContactVisibility(value);
            }
        }, ['change', 'blur']);

        // Message content validation with character counter
        setupFieldValidation('message_content', (field) => {
            const value = field.value.trim();
            const length = value.length;
            
            // Update character counter
            const countElement = document.getElementById('purpose_count');
            if (countElement) {
                countElement.textContent = length;
                countElement.classList.toggle('text-red-500', length > 500);
            }

            // Validation
            if (!value) {
                showMessageFieldError('message_error', 'Message content is required.');
            } else if (length < 10) {
                showMessageFieldError('message_error', 'Message content must be at least 10 characters long.');
            } else if (length > 1000) {
                showMessageFieldError('message_error', 'Message content must not exceed 1000 characters.');
            } else {
                showMessageFieldSuccess('message_content');
            }
        }, ['input', 'blur']);
    }

    function updateContactVisibility(selectedArea) {
        const contactItems = document.querySelectorAll('.contact-item');
        
        contactItems.forEach(item => {
            const location = item.getAttribute('data-location');
            const isVisible = selectedArea && location && 
                location.trim().toLowerCase() === selectedArea.trim().toLowerCase();
            
            item.style.display = isVisible ? 'flex' : 'none';
            
            const checkbox = item.querySelector('.contact-checkbox');
            if (checkbox) {
                checkbox.disabled = !isVisible;
                if (!isVisible) checkbox.checked = false;
            }
        });

        // Uncheck "all" checkbox and re-setup validation
        const allCheckbox = document.getElementById('contact_all');
        if (allCheckbox) allCheckbox.checked = false;
        
        setupContactValidation();
    }

    function setupContactValidation() {
        const allCheckbox = document.getElementById('contact_all');
        
        // Handle "all" checkbox
        if (allCheckbox) {
            allCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.contact-checkbox:not(:disabled)')
                        .forEach(cb => cb.checked = false);
                }
                validateContactSelection();
            });
        }

        // Handle individual contact checkboxes
        document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked && !this.disabled && allCheckbox) {
                    allCheckbox.checked = false;
                }
                validateContactSelection();
            });
        });
    }

    function validateContactSelection() {
        const allCheckbox = document.getElementById('contact_all');
        const checkedContacts = document.querySelectorAll('.contact-checkbox:checked:not(:disabled)');
        
        clearMessageFieldError('contact_type_error');
        clearMessageFieldSuccess('contact_type');
        
        if ((allCheckbox?.checked) || checkedContacts.length > 0) {
            showMessageFieldSuccess('contact_type');
        } else {
            showMessageFieldError('contact_type_error', 'At least one contact must be selected.');
        }
    }

    

    function clearAllMessageFieldErrors() {
        // Clear all error elements
        document.querySelectorAll('[id^="message"][id$="_error"]').forEach(element => {
            element.style.display = 'none';
            element.classList.add('hidden');
            element.style.visibility = 'hidden';
            element.style.opacity = '0';
            element.textContent = '';
        });

        // Clear field styling
        document.querySelectorAll('#messageForm .form-input, #messageForm .form-select, #messageForm .form-textarea')
            .forEach(field => field.classList.remove('form-field-invalid', 'form-field-valid'));
        
        // Hide error banner
        if (typeof window.closeBanner === 'function') {
            window.closeBanner();
        }
    }

    function validateMessageForm() {
        const errors = {};
        
        // Message type validation
        if (!document.getElementById('message_type').value) {
            errors.message_type = 'Message type is required.';
        }
        
        // Send mode validation
        const sendMode = document.querySelector('input[name="send_mode"]:checked');
        if (!sendMode) {
            errors.send_mode = 'Send mode is required.';
        }
        
        // Area and contact validation for single mode
        if (sendMode?.value === 'single') {
            if (!document.getElementById('selected_area').value) {
                errors.selected_area = 'Area selection is required for single mode.';
            }
            
            const allCheckbox = document.getElementById('contact_all');
            const checkedContacts = document.querySelectorAll('.contact-checkbox:checked:not(:disabled)');
            if (!allCheckbox?.checked && checkedContacts.length === 0) {
                errors.contact_type = 'At least one contact must be selected.';
            }
        }
        
        // Message content validation
        const messageContent = document.getElementById('message_content').value.trim();
        if (!messageContent) {
            errors.message = 'Message content is required.';
        } else if (messageContent.length < 10) {
            errors.message = 'Message content must be at least 10 characters long.';
        } else if (messageContent.length > 1000) {
            errors.message = 'Message content must not exceed 1000 characters.';
        }
        
        return errors;
    }

    function handleFormSubmission(messageForm) {
        return async function(e) {
            e.preventDefault();
            
            clearAllMessageFieldErrors();

            const errors = validateMessageForm();
            if (Object.keys(errors).length > 0) {
                showMessageErrorBanner();
                Object.keys(errors).forEach(field => 
                    showMessageFieldError(field + '_error', errors[field])
                );
                return;
            }

            const formData = new FormData(messageForm);
            const submitBtn = messageForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<div class="flex items-center"><div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Sending...</div>';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(messageForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                
                if (!response.ok) throw new Error(JSON.stringify(data));

                if (data.success) {
                    // Reset form and clear errors first
                    messageForm.reset();
                    clearAllMessageFieldErrors();
                    
                    // Function to show success banner
                    const showSuccess = () => {
                        showMessageSuccessBanner(data.message || 'Message sent successfully!');
                        setTimeout(() => window.location.reload(), 2500);
                    };
                    
                    // Function to wait for modal to be completely removed from DOM
                    const waitForModalToClose = () => {
                        return new Promise((resolve) => {
                            const checkModalGone = () => {
                                // Check if modal backdrop is completely gone
                                const modalBackdrop = document.getElementById('composeMessageModal');
                                const isModalGone = !modalBackdrop || 
                                    modalBackdrop.style.display === 'none' ||
                                    modalBackdrop.classList.contains('hidden') ||
                                    getComputedStyle(modalBackdrop).display === 'none';
                                
                                if (isModalGone) {
                                    resolve();
                                } else {
                                    setTimeout(checkModalGone, 50);
                                }
                            };
                            checkModalGone();
                        });
                    };
                    
                    // Close modal
                    closeComposeMessageModal();
                    await waitForModalToClose();
                    showSuccess();
                } else {
                    handleFormErrors(data);
                }
            } catch (error) {
                handleFormErrors(error.message ? JSON.parse(error.message) : {});
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        };
    }

    function handleFormErrors(data) {
        if (data.errors) {
            Object.keys(data.errors).forEach(field => 
                showMessageFieldError(field + '_error', data.errors[field][0])
            );
            showMessageErrorBanner();
        } else {
            const message = data.message || 'Failed to send message';
            if (typeof window.showBanner === 'function') {
                window.showBanner('error', 'Error: ' + message);
            } else {
                alert('Error: ' + message);
            }
        }
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const messageForm = document.getElementById('messageForm');
        
        if (messageForm) {
            setupMessageFormValidation();
            setupContactValidation();
            messageForm.addEventListener('submit', handleFormSubmission(messageForm));

        }

        // Download functionality
        const downloadBtn = document.getElementById('downloadMessagesBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const searchInput = document.querySelector('input[name="search"]');
                const searchParams = new URLSearchParams();
                
                if (searchInput?.value.trim()) {
                    searchParams.append('search', searchInput.value.trim());
                }
                
                let url = '{{ route("messages.export_pdf") }}';
                if (searchParams.toString()) {
                    url += '?' + searchParams.toString();
                }
                
                window.location.href = url;
            });
        }

        // Listen for custom close-modal event
        document.addEventListener('close-modal', function() {
            closeComposeMessageModal();
        });
    });

    // Modal functions (same approach as contacts.blade.php)
    function openComposeMessageModal() {
        document.getElementById('composeMessageModal').classList.remove('hidden');
        // Add class to body to hide header when modal is open
        document.body.classList.add('modal-open');
    }

    function closeComposeMessageModal() {
        document.getElementById('composeMessageModal').classList.add('hidden');
        // Remove class from body to show header when modal is closed
        document.body.classList.remove('modal-open');
    }

    // Close modal when clicking outside (same approach as contacts.blade.php)
    window.onclick = function(event) {
        const modal = document.getElementById('composeMessageModal');
        if (event.target == modal) {
            closeComposeMessageModal();
        }
    }

</script>

    <!-- Compose Message Modal -->
    <div id="composeMessageModal" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Compose Alert Message</h2>
                            <p class="text-blue-100 text-sm">Send notifications to contacts</p>
                        </div>
                    </div>
                    <button onclick="closeComposeMessageModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
             
            <div class="p-6 flex flex-col lg:flex-row gap-6">
                <!-- Compose Message Form -->
                <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-lg font-bold mb-6 text-gray-800 flex items-center gap-2">
                        <i class="fas fa-edit text-blue-600"></i> Message Configuration
                    </h3>
                    
                    <form id="messageForm" method="POST" action="{{ route('messages.send') }}" novalidate>
                        @csrf
                        <div class="space-y-6">
                             <!-- Message Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-bullhorn text-blue-600"></i>
                                    Message Type
                                </label>
                                <select id="message_type" name="message_type" class="form-select" required>
                                    <option value="">Select message type</option>
                                    <option value="alert">Alert</option>
                                    <option value="warning">Warning</option>
                                    <option value="advisory">Advisory</option>
                                    <option value="information">Information</option>
                                    <option value="emergency">Emergency</option>
                                </select>
                                <div id="message_type_error" class="error-message" style="display: none;"></div>
                            </div>

                            <!-- Send Mode -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-broadcast-tower text-blue-600"></i> Send Mode
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="radio" name="send_mode" value="all" x-model="sendMode" class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="font-medium">All areas with intense/torrential rain</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="radio" name="send_mode" value="single" x-model="sendMode" class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="font-medium">Single area</span>
                                    </label>
                                </div>
                                <div id="send_mode_error" class="error-message" style="display: none;"></div>
                            </div>

                            <!-- Select Area (for single mode) -->
                            <div x-show="sendMode === 'single'">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i> Select Area
                                    <span class="text-red-500">*</span>
                                </label>
                                <select id="selected_area" name="selected_area" class="form-select w-full {{ $errors->has('selected_area') ? 'form-field-invalid' : '' }}" x-model="selectedArea">
                                    <option value="">Choose specific area</option>
                                    @if(isset($devices))
                                        @foreach($devices as $device)
                                            <option value="{{ $device->dev_location }}">{{ $device->dev_location }} ({{ $device->latest_intensity ?? 'N/A' }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('selected_area'))
                                    <div class="error-message mt-1" style="display: block;">{{ $errors->first('selected_area') }}</div>
                                @else
                                    <div id="selected_area_error" class="error-message mt-1" style="display: none;"></div>
                                @endif
                                <div id="selected_area_success" class="success-message mt-1" style="display: none;"></div>
                            </div>

                            <!-- Contact Selection -->
                            <div x-show="sendMode === 'single' && selectedArea">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-users text-blue-600"></i> Select Contacts
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3 bg-white border border-gray-200 rounded-lg p-4">
                                    <label class="flex items-center gap-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg cursor-pointer">
                                        <input type="checkbox" name="contact_type[]" value="all" class="text-blue-600 focus:ring-blue-500" id="contact_all">
                                        <span>Send to ALL contacts in selected area</span>
                                    </label>

                                    @if(isset($contacts))
                                        @foreach($contacts as $contact)
                                            <label class="flex items-center gap-3 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors contact-item"
                                                   data-location="{{ $contact->brgy_location }}"
                                                   style="display: none;">
                                                <input type="checkbox" name="contact_type[]" value="{{ $contact->contact_id }}" class="text-blue-600 focus:ring-blue-500 contact-checkbox">
                                                <span>{{ trim(($contact->firstname ?? '') . ' ' . ($contact->middlename ?? '') . ' ' . ($contact->lastname ?? '')) ?: 'Unknown Contact' }} ({{ $contact->position ?? 'Contact' }})</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                                @if($errors->has('contact_type'))
                                    <div class="error-message mt-1" style="display: block;">{{ $errors->first('contact_type') }}</div>
                                @else
                                    <div id="contact_type_error" class="error-message mt-1" style="display: none;"></div>
                                @endif
                                <div id="contact_type_success" class="success-message mt-1" style="display: none;"></div>
                            </div>

                            <!-- Message Content -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-comment-alt text-blue-600"></i> Message Content
                                    <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message_content" name="message" class="form-textarea w-full {{ $errors->has('message') ? 'form-field-invalid' : '' }}" rows="4" x-model="messageContent" placeholder="Enter your alert message..." required></textarea>
                                @if($errors->has('message'))
                                    <div class="error-message mt-1" style="display: block;">{{ $errors->first('message') }}</div>
                                @else
                                    <div id="message_error" class="error-message mt-1" style="display: none;"></div>
                                @endif
                                <div id="message_success" class="success-message mt-1" style="display: none;"></div>
                    
                                <div class="text-xs text-gray-500">
                                    <span id="purpose_count">0</span>/500 characters
                                </div>
                            </div>

                            <!-- Send Button -->
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>

                 <!-- Recent Messages (Right) -->
                <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-3 border border-gray-200 flex flex-col">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2 flex-shrink-0">
                        <i class="fas fa-history text-blue-600"></i> Recent Messages
                    </h3>
                    <div class="flex-1 overflow-y-auto min-h-0">
                        @if($messages->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No messages sent yet</p>
                            </div>
                        @else
                            <div class="space-y-4 max-h-96 scrollbars-thin">
                                @foreach($messages->sortByDesc('date_created')->take(10) as $msg)
                                    <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="font-semibold text-gray-800">
                                                @php
                                                    $contactName = 'Unknown Contact';
                                                    if ($msg->contact) {
                                                        if (!empty($msg->contact->firstname) || !empty($msg->contact->lastname)) {
                                                            $contactName = trim(($msg->contact->firstname ?? '') . ' ' . ($msg->contact->middlename ?? '') . ' ' . ($msg->contact->lastname ?? ''));
                                                            $contactName = $contactName ?: 'Unknown Contact';
                                                        }
                                                    }
                                                @endphp
                                                {{ $contactName }}
                                            </div>
                                            <div class="font-semibold text-gray-800">{{ $msg->brgy_location ?? $msg->location }}</div>
                                            <span class="text-xs text-gray-400">{{ $msg->date_created ?? $msg->created_at }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">{{ $msg->text_desc ?? $msg->message }}</div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $msg->intensity_level ?? 'Unknown' }}</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">{{ $msg->status ?? 'Sent' }}</span>
                                            @php
                                                // Get composed by information for recent messages - get user information directly from the users table
                                                $composedBy = null;
                                                if (isset($msg->user) && $msg->user) {
                                                    // Get the full name from user fields directly
                                                    if (!empty($msg->user->first_name) || !empty($msg->user->last_name)) {
                                                        $composedBy = trim(($msg->user->first_name ?? '') . ' ' . ($msg->user->middle_name ?? '') . ' ' . ($msg->user->last_name ?? ''));
                                                    }
                                                    // Fallback to username if no name fields
                                                    $composedBy = $composedBy ?: $msg->user->username;
                                                }
                                            @endphp
                                            @if($composedBy)
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full">{{ ucfirst($composedBy) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

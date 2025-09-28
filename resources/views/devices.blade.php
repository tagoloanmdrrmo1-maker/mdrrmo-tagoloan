{{-- resources/views/devices.blade.php --}}
@extends('layouts.app')
@section('page_heading', 'Device Management')

@section('title', 'Device Management')

{{-- Data is provided by DeviceController --}}

@section('content')
<div class="container mx-auto px-4 py-6">


<style>
/* From Uiverse.io by adamgiebl */
.cssbuttons-io-button {
   display: inline-flex;
   align-items: center;
   font-family: inherit;
   cursor: pointer;
   font-weight: 500;
   font-size: inherit;
   padding: 0.6rem 1rem 0.5rem 0.65rem;
   color: white;
   background: #10b981;
   background: linear-gradient(
     0deg,
     rgba(16, 185, 129, 1) 0%,
     rgba(5, 150, 105, 1) 100%
   );
   border: none;
   box-shadow: 0 0.7em 1.5em -0.5em rgba(16, 185, 129, 0.6);
   letter-spacing: 0.05em;
   border-radius: 0.375rem;
   width: auto;
}

.min-w-full tbody tr:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

/* no long variant to keep original width */

.cssbuttons-io-button svg {
   margin-right: 8px;
}


.cssbuttons-io-button:hover {
   box-shadow: 0 0.5em 1.5em -0.5em rgba(16, 185, 129, 0.6);
}

.btn-add-device {
    background-color: #28a745; /* base green updated */
}
.btn-add-device:hover {
    background-color: #218838; /* requested hover */
}
.btn-open-add {
    background-color: #28a745; /* base green */
    border: 1px solid #28a745;
}
.btn-open-add:hover {
    background-color: #218838; /* hover green */
    border-color: #218838;
}
.form-input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}
.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.error-message {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
    align-items: center;
    gap: 0.25rem;

    
}

.error-message::before {
    content: "";
    margin-right: 0.5rem;
    font-size: 0.875rem;
}

/* Removed yellow warning icon from error messages */


.form-field-valid {
    border-color: #10b981 !important;
    background-color: #f0fdf4 !important;
}

.form-field-invalid {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}

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

/* Enhanced Action Buttons */
.action-btn {
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Search and Filter Styling */
.search-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m19 19-3.5-3.5m0 0a7 7 0 1 1-10-10 7 7 0 0 1 10 10Z'/%3e%3c/svg%3e");
    background-position: left 12px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-left: 40px;
}

/* Compact Edit Modal Styles */
#editModal .grid {
    gap: 1rem;
}

#editModal .space-y-4 > * + * {
    margin-top: 0.75rem;
}

#editModal input,
#editModal select {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

#editModal label {
    margin-bottom: 0.25rem;
}

#editModal .text-sm {
    font-size: 0.875rem;
}

#editModal .text-xs {
    font-size: 0.75rem;
}

/* Success Alert Positioning */
.success-alert {
    position: fixed !important;
    z-index: 9999999 !important;
    pointer-events: auto !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.success-alert {
    top: 1rem !important;
    right: 1rem !important;
    max-width: 400px;
}

/* Ensure banners are always visible */
/* Enhanced Modal */
#addModal,
#editModal {
    z-index: 9999 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    backdrop-filter: blur(4px);
    background-color: rgba(0, 0, 0, 0.5);
    align-items: center !important;
    justify-content: center !important;
}

/* Ensure modals are hidden by default */
#addModal.hidden,
#editModal.hidden {
    display: none !important;
}

/* Show modals when not hidden */
#addModal:not(.hidden),
#editModal:not(.hidden) {
    display: flex !important;
}



/* Responsive adjustments for edit modal */
@media (max-width: 768px) {
    #editModal .max-w-4xl {
        max-width: 95vw;
        margin: 0.5rem;
    }

    #editModal .grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }

    #editModal .grid.grid-cols-2 {
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    #editModal .grid.grid-cols-2 {
        grid-template-columns: 1fr;
    }

    #editModal .max-h-modal {
        max-height: 90vh;
    }
}

</style>
{{-- Status Overview --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Devices</p>
                <p class="text-2xl font-bold text-gray-900">{{ $report4->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-microchip text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $report4->where('status','active')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending</p>
                <p class="text-2xl font-bold text-red-600">{{ $report4->where('status','pending')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between gap-4">
        <!-- Search Section -->
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('devices.index') }}" class="flex items-center gap-2">
                <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-80"
                                placeholder="Search devices..."
                            >
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-4 bg-gray-700 text-white rounded-r-lg">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
            </form>
            
            <!-- Status Filter -->
            <form method="GET" action="{{ route('devices.index') }}" class="flex items-center">
                <input type="hidden" name="search" value="{{ request('search') }}" />
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </form>
        </div>
                
        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Device
            </button>
            <button
                id="downloadDevicesBtn"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2"
            >
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>
</div>
     <!-- Devices Table Container -->
            <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
            <div class="px-5 py-3 rounded-t-xl bg-[#242F41] text-white">
                <h3 class="text-base font-medium flex items-center gap-2">
                    <i class="fas fa-microchip w-4"></i>
                    Rain Gauge Devices
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table id="devices-table" class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Device ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Serial Number</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Location</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 tracking-wider">Current Rainfall</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 tracking-wider">Added By</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 tracking-wider">Intensity Level</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 tracking-wider">Action</th>
                </tr>
            </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($report4 as $data)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}" data-device-id="{{ $data->dev_id }}">
                                <td class="px-5 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $data->dev_id }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $data->serial_number ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $data->dev_location }}</td>
                                <td class="px-5 py-3 text-center text-sm text-gray-700 whitespace-nowrap">{{ isset($data->latest_rainfall) ? number_format($data->latest_rainfall, 1) . ' mm' : 'N/A'  }}</td>
                                <td class="px-5 py-3 text-center text-sm whitespace-nowrap">{{ $data->first_name ? $data->first_name . ' ' . $data->last_name : ($data->username ?? $data->added_by ?? '-') }}</td>
                                <td class="px-5 py-3 text-sm whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($data->status === 'active') bg-green-100 text-green-800
                                @elseif($data->status === 'inactive') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $data->status ?? 'Unknown' }}
                            </span>
                                </td>
                                <td class="px-5 py-3 text-center text-sm whitespace-nowrap">
                            @if(isset($data->latest_intensity) && $data->latest_intensity)
                                @php
                                    $colorClass = match($data->latest_intensity) {
                                                'Torrential' => 'bg-red-100 text-red-800 border-red-200',
                                                'Intense' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'Heavy' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'Moderate' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold border {{ $colorClass }}">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"></svg>
                                    {{ $data->latest_intensity }}
                                </span>
                            @else
                                        <span class="text-gray-400 text-xs">-</span>
                            @endif
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 text-white rounded-full hover:bg-amber-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2 mr-2 shadow-sm transition-colors"
                                onclick="openEditModal(`{{ $data->dev_id }}`)"
                                aria-label="Edit"
                                title="Edit"
                            >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"/><path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"/></svg>
                            </button>
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 text-white rounded-full hover:bg-rose-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2 shadow-sm transition-colors"
                                onclick="deleteDevice(`{{ $data->dev_id }}`)"
                                aria-label="Delete"
                                title="Delete"
                            >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.527 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.951.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5 .058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48 .058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5 .058l.345-9z" clip-rule="evenodd"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No devices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- No devices found message for search results -->
    <div id="noDevicesFoundMessage" class="hidden bg-white px-4 py-8 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No devices found</h3>
            <p class="text-gray-500">Try adjusting your search criteria or filters to find what you're looking for.</p>
        </div>
    </div>

    <!-- Results summary (no page numbers, matches rainfall style) -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div>
            @php
                $isPaginated = method_exists($report4, 'firstItem');
                $totalResults = $isPaginated && method_exists($report4, 'total')
                    ? $report4->total()
                    : (is_countable($report4) ? count($report4) : 0);
                $firstItem = $isPaginated ? ($report4->firstItem() ?? 0) : ($totalResults > 0 ? 1 : 0);
                $lastItem  = $isPaginated ? ($report4->lastItem() ?? 0)  : $totalResults;
            @endphp
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ $firstItem }}</span>
                to <span class="font-medium">{{ $lastItem }}</span>
                of <span class="font-medium">{{ $totalResults }}</span> results
            </p>
        </div>
    </div>
    
    <!-- Pagination Controls -->
    @if($isPaginated && $report4->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <!-- Mobile Pagination (Previous & Next only) -->
        <div class="flex-1 flex justify-between sm:hidden">
            @if($report4->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $report4->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @endif

            @if($report4->hasMorePages())
                <a href="{{ $report4->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                    <span class="font-medium">{{ $report4->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $report4->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $report4->total() }}</span>
                    results
                </p>
            </div>

            <!-- Numbers + Previous/Next -->
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Previous Page Link -->
                    @if($report4->onFirstPage())
                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $report4->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md">Previous</a>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($report4->getUrlRange(1, $report4->lastPage()) as $page => $url)
                        @if ($page == $report4->currentPage())
                            <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if($report4->hasMorePages())
                        <a href="{{ $report4->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">Next</a>
                    @else
                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">Next</span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @endif
    
<!-- Edit Device Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4 hidden backdrop-blur-sm" onclick="closeEditModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Edit Device</h3>
                            <p class="text-blue-100 text-sm">Update device location and status</p>
                        </div>
                    </div>
                    <button onclick="closeEditModal()" class="text-white hover:text-blue-200 transition-colors text-2xl leading-none">&times;</button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <form id="editDeviceForm" method="POST" action="" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_serial_number" class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-microchip text-blue-600"></i>
                                    Serial Number
                                    <span class="text-red-500">*</span>
                                </span>
                                <div class="relative">
                                    <input type="text" id="edit_serial_number" name="serial_number" class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-8 bg-gray-100 cursor-not-allowed text-sm" required readonly>
                                    <i id="edit_serial_number_icon" class="validation-icon hidden fas fa-check text-green-500"></i>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label for="edit_dev_location" class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                    Location
                                    <span class="text-red-500">*</span>
                                </span>
                                <div class="relative">
                                    <select
                                        id="edit_dev_location"
                                        name="dev_location"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm"
                                        required
                                    >
                                        <option value="">Select a location</option>
                                        <option value="Baluarte">Baluarte</option>
                                        <option value="Casinglot">Casinglot</option>
                                        <option value="Gracia">Gracia</option>
                                        <option value="Mohon">Mohon</option>
                                        <option value="Natumolan">Natumolan</option>
                                        <option value="Poblacion">Poblacion</option>
                                        <option value="Rosario">Rosario</option>
                                        <option value="Santa Ana">Santa Ana</option>
                                        <option value="Santa Cruz">Santa Cruz</option>
                                        <option value="Sugbongcogon">Sugbongcogon</option>
                                    </select>
                                    <i id="edit_dev_location_icon" class="validation-icon hidden"></i>
                                </div>
                                <div id="edit_dev_location_error" class="error-message mt-1" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label for="edit_status" class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                    Status
                                    <span class="text-red-500">*</span>
                                </span>
                                <select id="edit_status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm" required>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_cumulative_rainfall" class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-cloud-rain text-blue-600"></i>
                                    Latest Rainfall (mm)
                                </span>
                                <input type="number" step="0.01" id="edit_cumulative_rainfall" name="cumulative_rainfall" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed text-sm" required readonly>
                            </label>
                        </div>
                        </div>

                        <div>
                            <label for="edit_date_installed" class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                    Date Installed
                                </span>
                                <input type="date" id="edit_date_installed" name="date_installed" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed text-sm" required readonly>
                            </label>
                        </div>

                        <!-- Coordinates in a compact layout -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="edit_latitude" class="block">
                                    <span class="block text-xs font-semibold text-gray-600 mb-1">Latitude</span>
                                    <input type="number" step="0.000001" id="edit_latitude" name="latitude" class="w-full border border-gray-300 rounded px-2 py-1 bg-gray-100 cursor-not-allowed text-xs" required readonly>
                                </label>
                            </div>
                            <div>
                                <label for="edit_longitude" class="block">
                                    <span class="block text-xs font-semibold text-gray-600 mb-1">Longitude</span>
                                    <input type="number" step="0.000001" id="edit_longitude" name="longitude" class="w-full border border-gray-300 rounded px-2 py-1 bg-gray-100 cursor-not-allowed text-xs" required readonly>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Device
                        </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Device Modal -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative transform transition-all duration-300 scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-microchip text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Add New Device</h2>
                            <p class="text-blue-100 text-sm">Create new device record</p>
                        </div>
                    </div>
                    <button onclick="closeAddModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="addDeviceForm" method="POST" action="{{ route('devices.store') }}" novalidate>
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-microchip text-blue-600"></i>
                                    Serial Number
                                    <span class="text-red-500">*</span>
                                </span>
                                <div class="relative">
                                    <input id="add_serial_number" type="text" name="serial_number" value="{{ old('serial_number') }}" required class="w-full border {{ $errors->has('serial_number') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg px-4 py-3 focus:outline-none focus:ring-2 transition-all duration-200" placeholder="e.g., DEV001, RG2024">
                                    <i id="add_serial_number_icon" class="validation-icon hidden"></i>
                                </div>
                                @if($errors->has('serial_number'))
                                    <div class="error-message mt-1" style="display: block; visibility: visible; opacity: 1;">{{ $errors->first('serial_number') }}</div>
                                @else
                                    <div id="add_serial_number_error" class="error-message mt-1" style="display: none; visibility: hidden; opacity: 0;"></div>
                                @endif
                                <div id="add_serial_number_success" class="success-message mt-1" style="display: none;"></div>
                            </label>
                        </div>
                        
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                    Device Location
                                    <span class="text-red-500">*</span>
                                </span>
                                <div class="relative">
                                    <select id="add_dev_location" name="dev_location" required class="w-full border {{ $errors->has('dev_location') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg px-4 py-3 focus:outline-none focus:ring-2 transition-all duration-200">
                                        <option value="">Select a location</option>
                                        <option value="Baluarte" {{ old('dev_location') === 'Baluarte' ? 'selected' : '' }}>Baluarte</option>
                                        <option value="Casinglot" {{ old('dev_location') === 'Casinglot' ? 'selected' : '' }}>Casinglot</option>
                                        <option value="Gracia" {{ old('dev_location') === 'Gracia' ? 'selected' : '' }}>Gracia</option>
                                        <option value="Mohon" {{ old('dev_location') === 'Mohon' ? 'selected' : '' }}>Mohon</option>
                                        <option value="Natumolan" {{ old('dev_location') === 'Natumolan' ? 'selected' : '' }}>Natumolan</option>
                                        <option value="Poblacion" {{ old('dev_location') === 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
                                        <option value="Rosario" {{ old('dev_location') === 'Rosario' ? 'selected' : '' }}>Rosario</option>
                                        <option value="Santa Ana" {{ old('dev_location') === 'Santa Ana' ? 'selected' : '' }}>Santa Ana</option>
                                        <option value="Santa Cruz" {{ old('dev_location') === 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                                        <option value="Sugbongcogon" {{ old('dev_location') === 'Sugbongcogon' ? 'selected' : '' }}>Sugbongcogon</option>
                                    </select>
                                    <i id="add_dev_location_icon" class="validation-icon hidden"></i>
                                </div>
                                @if($errors->has('dev_location'))
                                    <div class="error-message mt-1" style="display: block; visibility: visible; opacity: 1;">{{ $errors->first('dev_location') }}</div>
                                @else
                                    <div id="add_dev_location_error" class="error-message mt-1" style="display: none; visibility: hidden; opacity: 0;">Select a location required</div>
                                @endif
                            </label>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" onclick="closeAddModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Add Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header with warning icon -->
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Confirm Deletion</h3>
                    </div>
                    <button type="button" class="ml-auto text-gray-400 hover:text-gray-600" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Delete icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
                    </div>
                </div>
                
                <!-- Confirmation message -->
                <div class="text-center mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Delete Device</h4>
                    <p class="text-gray-600">
                        Are you sure you want to delete the device <span id="deleteDeviceName" class="font-semibold"></span>? This action cannot be undone.
                    </p>
                </div>
                
                <!-- Action buttons -->
                <div class="flex justify-center space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors" onclick="closeDeleteModal()">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors" onclick="confirmDeleteDevice()">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@if($errors->any())
<script>
    // Auto-open the Add modal when there are validation errors from store
    document.addEventListener('DOMContentLoaded', function() {
        openAddModal();
    });
</script>
@endif

<!-- Inline JavaScript for device functionality -->
<script>
// Device search functionality
function initializeDeviceSearch() {
    const deviceSearchInput = document.querySelector('input[name="search"]');
    const deviceTableRows = document.querySelectorAll('#devices-table tbody tr');

    if (deviceSearchInput) {
        let searchTimeout;

        // Real-time search with debounce
        deviceSearchInput.addEventListener('input', function() {
            const clearBtn = document.getElementById('clear_device_search_btn');

            // Show/hide clear button based on input
            if (this.value.trim()) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Debounce search to avoid too many calls while typing
            searchTimeout = setTimeout(() => {
                filterDeviceTable();
            }, 300); // 300ms delay
        });

        // Handle Enter key
        deviceSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Clear timeout and search immediately
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                filterDeviceTable();
            }
        });
    }

    function filterDeviceTable() {
        const searchTerm = deviceSearchInput ? deviceSearchInput.value.toLowerCase().trim() : '';

        let visibleCount = 0;
        deviceTableRows.forEach(row => {
            // Skip the "No devices found" row
            if (row.querySelector('td[colspan]')) {
                return;
            }

            // Get text content from multiple columns for comprehensive search
            const deviceId = row.cells[0].textContent.toLowerCase();
            const serialNumber = row.cells[1].textContent.toLowerCase();
            const location = row.cells[2].textContent.toLowerCase();
            const status = row.cells[4].textContent.toLowerCase();

            // Check if all typed letters appear anywhere in the searchable fields
            const searchableText = `${deviceId} ${serialNumber} ${location} ${status}`;

            // If no search term, show all rows
            if (searchTerm === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                // Check if all letters from search term appear in the searchable text
                let allLettersFound = true;
                for (let i = 0; i < searchTerm.length; i++) {
                    if (!searchableText.includes(searchTerm[i])) {
                        allLettersFound = false;
                        break;
                    }
                }

                if (allLettersFound) {
                    row.style.display = ''; // Show row
                    visibleCount++;
                } else {
                    row.style.display = 'none'; // Hide row
                }
            }
        });

        // Update results count
        updateDeviceResultsCount(visibleCount);

        // Handle no results display
        handleNoResultsDisplay(visibleCount, searchTerm);
    }

    // Update results count based on visible rows
    function updateDeviceResultsCount(visibleCount) {
        // Update the results summary text
        const resultsSummary = document.querySelector('.bg-white.px-4.py-3.flex.items-center.justify-between.border-t.border-gray-200.sm\\:px-6');
        if (resultsSummary) {
            const firstItem = visibleCount > 0 ? 1 : 0;
            const lastItem = visibleCount;

            resultsSummary.innerHTML = `
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">${firstItem}</span>
                        to <span class="font-medium">${lastItem}</span>
                        of <span class="font-medium">${visibleCount}</span> results
                    </p>
                </div>
            `;
        }
    }

    // Initialize results count on page load
    if (deviceTableRows.length > 0) {
        // Count only actual data rows, not the "No devices found" row
        const dataRows = Array.from(deviceTableRows).filter(row => !row.querySelector('td[colspan]'));
        updateDeviceResultsCount(dataRows.length);
        // Handle no results display on page load
        handleNoResultsDisplay(dataRows.length, '');
    }
}

// Handle no results display
function handleNoResultsDisplay(visibleCount, searchTerm) {
    const noResultsMessage = document.getElementById('noDevicesFoundMessage');
    const devicesTable = document.getElementById('devices-table');

    if (!noResultsMessage || !devicesTable) {
        return;
    }

    // Show no results message only if there are no visible rows and there's a search term
    if (visibleCount === 0 && searchTerm !== '') {
        // Hide the table
        devicesTable.style.display = 'none';
        // Show the no results message
        noResultsMessage.classList.remove('hidden');
    } else {
        // Show the table
        devicesTable.style.display = '';
        // Hide the no results message
        noResultsMessage.classList.add('hidden');
    }
}

// Clear device search functionality
function clearDeviceSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    const clearBtn = document.getElementById('clear_device_search_btn');

    if (searchInput) {
        searchInput.value = '';
        clearBtn.classList.add('hidden');

        // Show all rows
        const deviceTableRows = document.querySelectorAll('#devices-table tbody tr');
        let visibleCount = 0;

        deviceTableRows.forEach(row => {
            // Skip the "No devices found" row
            if (row.querySelector('td[colspan]')) {
                return;
            }
            row.style.display = '';
            visibleCount++;
        });

        // Update results count
        updateDeviceResultsCount(visibleCount);

        // Handle no results display when clearing search
        handleNoResultsDisplay(visibleCount, '');
    }
}

// Load notifications function (if needed)
function loadNotifications() {
    // Placeholder function - implement if needed
    console.log('loadNotifications called');
}

// Test function to verify validation is working
function testDeviceValidation() {
    console.log('Testing device validation...');

    // Test serial number validation
    const testSerials = ['', 'ABC', 'ABC1234567890123456789012345678901', 'ABC-123_DEF', 'abc123', 'ABC@123'];
    testSerials.forEach((serial, index) => {
        const mockInput = { value: serial };
        const upperSerial = serial.toUpperCase();
        console.log(`Test ${index + 1}: "${serial}" -> "${upperSerial}"`);

        if (!upperSerial) {
            console.log('  Empty serial correctly rejected');
        } else if (upperSerial.length < 6) {
            console.log('  Short serial correctly rejected');
        } else if (upperSerial.length > 50) {
            console.log('  Long serial correctly rejected');
        } else if (!/^[A-Z0-9_-]+$/.test(upperSerial)) {
            console.log('  Invalid characters correctly rejected');
        } else {
            console.log('  Valid serial format accepted');
        }
    });

    console.log('Device validation test completed.');
}

document.getElementById('downloadDevicesBtn')?.addEventListener('click', function(e) {
    e.preventDefault();

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
    let url = '/devices/export-pdf';
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

function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    clearAllFieldErrors();
    requestDataForm.reset();
}

function openEditModal(deviceId) {
    console.log('Opening edit modal for device:', deviceId);

    // Fetch device data
    fetch(`/devices/${deviceId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Device data received:', data);

            if (data.error) {
                showMessage('error', data.error);
                return;
            }

            // Populate form fields
            document.getElementById('edit_serial_number').value = data.serial_number || data.dev_id;
            document.getElementById('edit_dev_location').value = data.dev_location;
            document.getElementById('edit_cumulative_rainfall').value = data.cumulative_rainfall;
            document.getElementById('edit_date_installed').value = data.date_installed;
            document.getElementById('edit_latitude').value = data.latitude;
            document.getElementById('edit_longitude').value = data.longitude;
            document.getElementById('edit_status').value = data.status;

            // Set form action URL
            document.getElementById('editDeviceForm').action = `/devices/${deviceId}`;

            // Store device ID for form submission
            document.getElementById('editDeviceForm').setAttribute('data-device-id', deviceId);

            // Show modal
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            console.log('Modal should be visible now. Modal element:', modal);
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'Failed to load device data');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');

    // Clear any validation errors when modal is closed
    clearFieldError('edit_dev_location_error');
    clearFieldSuccess('edit_dev_location');

    // Reset form styling
    const locationInput = document.getElementById('edit_dev_location');
    if (locationInput) {
        locationInput.classList.remove('form-field-invalid', 'form-field-valid');
    }
}

function deleteDevice(deviceId) {
    // Get device location from the table for display
    const deviceRow = document.querySelector(`tr[data-device-id="${deviceId}"]`);
    let deviceLocation = 'this device';
    if (deviceRow) {
        const locationCell = deviceRow.querySelector('td:nth-child(3)');
        if (locationCell) {
            deviceLocation = `"${locationCell.textContent.trim()}"`;
        }
    }

    // Set device name in modal
    document.getElementById('deleteDeviceName').textContent = deviceLocation;

    // Store device ID for confirmation
    document.getElementById('confirmDeleteBtn').setAttribute('data-device-id', deviceId);

    // Show confirmation modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDeleteDevice() {
    const deviceId = document.getElementById('confirmDeleteBtn').getAttribute('data-device-id');

    if (!deviceId) {
        console.error('No device ID found for deletion');
        return;
    }

    // Create a form to submit DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/devices/${deviceId}`;
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add DELETE method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    // Close modal first
    closeDeleteModal();

    // Submit form
    document.body.appendChild(form);
    form.submit();
}

function showMessage(type, message) {
    const container = document.getElementById('messageContainer');
    const successMsg = document.getElementById('successMessage');
    const errorMsg = document.getElementById('errorMessage');

    container.style.display = 'block';

    if (type === 'success') {
        successMsg.textContent = message;
        successMsg.style.display = 'block';
        errorMsg.style.display = 'none';
    } else {
        errorMsg.textContent = message;
        errorMsg.style.display = 'block';
        successMsg.style.display = 'none';
    }

    // Auto-hide after 5 seconds
    setTimeout(() => {
        container.style.display = 'none';
    }, 5000);
}

// Function to show success alert
function showSuccessAlert(message) {
    console.log('showSuccessAlert called with message:', message);

    // Remove any existing alerts
    const existingAlert = document.querySelector('.success-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Ensure the message is not empty
    if (!message || message.trim() === '') {
        console.log('Empty message provided to showSuccessAlert, skipping');
        return;
    }

    // Determine styling based on message type
    let bgColor, borderColor, textColor, iconClass;
    console.log('Checking message for delete keywords...');
    console.log('Message toLowerCase():', message.toLowerCase());
    console.log('Contains "deleted":', message.toLowerCase().includes('deleted'));
    console.log('Contains "delete":', message.toLowerCase().includes('delete'));

    if (message.toLowerCase().includes('deleted') || message.toLowerCase().includes('delete')) {
        console.log('Using RED styling for delete operation');
        // Red styling for delete operations
        bgColor = 'bg-red-100';
        borderColor = 'border-red-400';
        textColor = 'text-red-700';
        iconClass = 'fas fa-trash-alt';
    } else {
        console.log('Using GREEN styling for add/update operation');
        // Green styling for add/update operations
        bgColor = 'bg-green-100';
        borderColor = 'border-green-400';
        textColor = 'text-green-700';
        iconClass = 'fas fa-check-circle';
    }

    console.log('Final styling:', { bgColor, borderColor, textColor, iconClass });

    // Create alert element - centered at top with 80px offset
    const alert = document.createElement('div');
    alert.className = `success-alert fixed left-1/2 transform -translate-x-1/2 ${bgColor} ${borderColor} ${textColor} px-6 py-4 rounded-lg shadow-lg z-[9999999] transition-all duration-300 opacity-0 translate-y-[-20px]`;
    alert.style.top = '80px'; // 80px from top as per user preference
    alert.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="${iconClass} text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

    console.log('Alert element created with className:', alert.className);

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

    // Don't show error icon - remove this functionality
    const iconId = fieldId + '_icon';
    const icon = document.getElementById(iconId);
    if (icon) {
        icon.style.display = 'none'; // Hide the icon instead of showing error icon
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

    // Don't show any icons
    const iconId = fieldId + '_icon';
    const icon = document.getElementById(iconId);
    if (icon) {
        icon.style.display = 'none'; // Hide the icon completely
    }
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

// Function to validate device form (Add Device)
function validateDeviceForm() {
    const errors = {};
    let hasErrors = false;

    console.log('Starting device form validation...');

    // Clear previous errors
    clearFieldError('add_serial_number_error');
    clearFieldError('add_dev_location_error');

    // Get form elements
    const serialInput = document.getElementById('add_serial_number');
    const locationInput = document.getElementById('add_dev_location');

    console.log('Form elements found:', { serialInput: !!serialInput, locationInput: !!locationInput });

    if (!serialInput || !locationInput) {
        console.error('Form elements not found');
        errors.general = 'Form elements not found. Please refresh the page.';
        hasErrors = true;
        return { errors, hasErrors };
    }

    // Serial number validation
    const serialNumber = serialInput.value.trim();
    console.log('Validating serial number:', serialNumber);

    if (!serialNumber) {
        errors.add_serial_number_error = 'Serial number is required.';
        hasErrors = true;
    } else {
        const upperSerial = serialNumber.toUpperCase();
        if (upperSerial.length < 6) {
            errors.add_serial_number_error = 'Serial number must be at least 6 characters long.';
            hasErrors = true;
        } else if (upperSerial.length > 50) {
            errors.add_serial_number_error = 'Serial number cannot exceed 50 characters.';
            hasErrors = true;
        } else if (!/^[A-Z0-9_-]+$/.test(upperSerial)) {
            errors.add_serial_number_error = 'Serial number can only contain uppercase letters, numbers, underscores, and hyphens.';
            hasErrors = true;
        } else {
            // Update the input value to uppercase
            serialInput.value = upperSerial;
        }
    }

    // Device location validation
    const devLocation = locationInput.value;
    console.log('Validating device location:', devLocation);

    if (!devLocation || devLocation === '') {
        errors.add_dev_location_error = 'Select a location required';
        hasErrors = true;
    }

    // Display errors if any
    if (hasErrors) {
        console.log('Validation errors found:', errors);
        Object.keys(errors).forEach(errorId => {
            if (errorId !== 'general') {
                console.log('Showing field error for:', errorId, 'with message:', errors[errorId]);
                showFieldError(errorId, errors[errorId]);
            }
        });

        // Show general error if any
        if (errors.general) {
            console.log('Showing general error banner:', errors.general);
            showBanner('error', errors.general);
        }
    } else {
        console.log('Validation passed successfully');
    }

    return { errors, hasErrors };
}

// Function to check if location already exists
function checkLocationExists(location, excludeDeviceId = null) {
    return new Promise((resolve) => {
        if (!location || location.trim() === '') {
            resolve(false);
            return;
        }

        fetch('/devices/check-location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                location: location.trim(),
                exclude_device_id: excludeDeviceId
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

// Function to check if serial number already exists
function checkSerialExists(serialNumber, excludeDeviceId = null) {
    return new Promise((resolve) => {
        if (!serialNumber || serialNumber.trim() === '') {
            resolve(false);
            return;
        }

        fetch('/devices/check-serial', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                serial_number: serialNumber.trim().toUpperCase(),
                exclude_device_id: excludeDeviceId
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

// Function to validate edit device form
function validateEditDeviceForm() {
    const errors = {};
    let hasErrors = false;

    console.log('Starting edit device form validation...');

    // Clear previous errors
    clearFieldError('edit_dev_location_error');

    // Get form elements
    const locationInput = document.getElementById('edit_dev_location');

    if (!locationInput) {
        console.error('Edit form elements not found');
        errors.general = 'Form elements not found. Please refresh the page.';
        hasErrors = true;
        return { errors, hasErrors };
    }

    // Device location validation
    const devLocation = locationInput.value;
    console.log('Validating edit device location:', devLocation);

    if (!devLocation || devLocation === '') {
        errors.edit_dev_location_error = 'Select a location required';
        hasErrors = true;
    }

    // Display errors if any
    if (hasErrors) {
        console.log('Edit validation errors found:', errors);
        Object.keys(errors).forEach(errorId => {
            if (errorId !== 'general') {
                showFieldError(errorId, errors[errorId]);
            }
        });

        // Show general error if any
        if (errors.general) {
            showBanner('error', errors.general);
        }
    } else {
        console.log('Edit validation passed successfully');
    }

    return { errors, hasErrors };
}

// Add form submission handler for the add form
document.addEventListener('DOMContentLoaded', function() {
    // Ensure modals are hidden on page load
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    if (addModal) {
        addModal.classList.add('hidden');
    }

    if (editModal) {
        editModal.classList.add('hidden');
    }

    // Initialize device search functionality
    initializeDeviceSearch();

    // Test validation functions (only in development)
    if (typeof window !== 'undefined' && window.location.hostname === 'localhost') {
        console.log('Running device validation tests...');
        testDeviceValidation();
    }

    // Add form submission handler
    const addForm = document.querySelector('#addDeviceForm');
    if (addForm) {
        console.log('Add form found, setting up submission handler');

        addForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();



            console.log('Add form submitted, starting validation...');

            // Store form data for potential retry
            const formData = new FormData(this);

            // Clear any existing errors first
            clearFieldError('add_serial_number_error');
            clearFieldError('add_dev_location_error');

            // Client-side validation
            const validation = validateDeviceForm();

            if (validation.hasErrors) {
                console.log('Add validation failed:', validation.errors);
                // Show general error message
                showBanner('error', 'Please fix the validation errors before submitting.');

                return; // Don't submit the form
            }

            // Sanitize inputs
            const serialInput = document.getElementById('add_serial_number');
            const locationInput = document.getElementById('add_dev_location');

            if (serialInput) {
                serialInput.value = sanitizeInput(serialInput.value.trim());
            }

            if (locationInput) {
                locationInput.value = sanitizeInput(locationInput.value.trim());
            }

            // Check for duplicate serial number
            if (serialInput && serialInput.value) {
                console.log('Checking serial number availability...');
                const serialExists = await checkSerialExists(serialInput.value);
                if (serialExists) {
                    showFieldError('add_serial_number_error', 'This serial number is already in use.');
                    showBanner('error', 'This serial number is already in use.');

                    // Re-enable submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Add';
                    }

                    return; // Don't submit the form
                }
            }

            // Check for duplicate location
            if (locationInput && locationInput.value) {
                console.log('Checking location availability...');
                const locationExists = await checkLocationExists(locationInput.value);
                if (locationExists) {
                    showFieldError('add_dev_location_error', 'This location is already in use by another device.');
                    showBanner('error', 'This location is already in use by another device.');

                    // Re-enable submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Add';
                    }

                    return; // Don't submit the form
                }
            }

            console.log('Add validation passed, submitting form...');

            // Submit the form directly - the success message will be handled by handleSuccessMessages()
            this.submit();

            // Re-enable submit button after a short delay (in case form submission is slow)
            setTimeout(() => {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Add';
                }
            }, 2000);

        });
    }
});

// Add real-time validation as user types
document.addEventListener('DOMContentLoaded', function() {
    // Real-time validation for add form
    const addSerialInput = document.getElementById('add_serial_number');
    const addLocationInput = document.getElementById('add_dev_location');

    if (addSerialInput) {
        let serialCheckTimeout;
        addSerialInput.addEventListener('input', function() {
            const value = this.value.trim();
            const upperValue = value.toUpperCase();
            this.value = upperValue; // Auto-uppercase
            clearFieldError('add_serial_number_error');

            // Clear previous timeout
            if (serialCheckTimeout) {
                clearTimeout(serialCheckTimeout);
            }

            // Real-time validation
            if (!value) {
                clearFieldError('add_serial_number_error');
                clearFieldSuccess('add_serial_number');
            } else if (value.length < 6) {
                showFieldError('add_serial_number_error', 'Serial number must be at least 6 characters long.');
                clearFieldSuccess('add_serial_number');
            } else if (value.length > 50) {
                showFieldError('add_serial_number_error', 'Serial number cannot exceed 50 characters.');
                clearFieldSuccess('add_serial_number');
            } else if (!/^[A-Z0-9_-]+$/.test(upperValue)) {
                showFieldError('add_serial_number_error', 'Serial number can only contain uppercase letters, numbers, underscores, and hyphens.');
                clearFieldSuccess('add_serial_number');
            } else {
                // Valid format - show success and check for duplicates
                clearFieldError('add_serial_number_error');
                showFieldSuccess('add_serial_number');

                // Check for duplicate serial number after user stops typing (debounced)
                serialCheckTimeout = setTimeout(async () => {
                    const serialExists = await checkSerialExists(upperValue);
                    if (serialExists) {
                        showFieldError('add_serial_number_error', 'This serial number is already in use.');
                        clearFieldSuccess('add_serial_number');
                    } else {
                        showFieldSuccess('add_serial_number');
                    }
                }, 500); // 500ms delay
            }
        });
    }

    if (addLocationInput) {
        let locationCheckTimeout;
        addLocationInput.addEventListener('input', function() {
            clearFieldError('add_dev_location_error');

            const value = this.value.trim();
            // Clear previous timeout
            if (locationCheckTimeout) {
                clearTimeout(locationCheckTimeout);
            }

            // Real-time validation for select dropdown
            if (!value || value === '') {
                showFieldError('add_dev_location_error', 'Select a location required');
                clearFieldSuccess('add_dev_location');
            } else {
                clearFieldError('add_dev_location_error');
                showFieldSuccess('add_dev_location', 'Location selected');

                locationCheckTimeout = setTimeout(async () => {
                    const locationExists = await checkLocationExists(value);
                    if (locationExists) {
                        showFieldError('add_dev_location_error', 'This location is already in use.');
                        clearFieldSuccess('add_dev_location');
                    } else {
                        showFieldSuccess('add_dev_location', 'Location is available');
                    }
                }, 500); // 500ms delay
            }
        });
    }

    // Real-time validation for edit form
    const editLocationInput = document.getElementById('edit_dev_location');

    if (editLocationInput) {
        let locationCheckTimeout;
        editLocationInput.addEventListener('input', function() {
            clearFieldError('edit_dev_location_error');

            const value = this.value.trim();
            // Clear previous timeout
            if (locationCheckTimeout) {
                clearTimeout(locationCheckTimeout);
            }

            // Real-time validation for select dropdown
            if (!value || value === '') {
                showFieldError('edit_dev_location_error', 'Select a location required');
                clearFieldSuccess('edit_dev_location');
            } else {
                clearFieldError('edit_dev_location_error');
                showFieldSuccess('edit_dev_location', 'Location selected');
            }
        });
    }

    // Edit form submission handler
    const editForm = document.getElementById('editDeviceForm');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Edit form submitted, starting validation...');

            // Clear any existing errors first
            clearFieldError('edit_dev_location_error');

            // Client-side validation
            const validation = validateEditDeviceForm();

            if (validation.hasErrors) {
                console.log('Edit validation failed:', validation.errors);
                // Show general error message
                showBanner('error', 'Please fix the validation errors before submitting.');
                return; // Don't submit the form
            }

            // Sanitize inputs
            const locationInput = document.getElementById('edit_dev_location');
            const deviceId = this.getAttribute('data-device-id');

            if (locationInput) {
                locationInput.value = sanitizeInput(locationInput.value.trim());
            }

            // Check for duplicate location (excluding current device)
            if (locationInput && locationInput.value && deviceId) {
                console.log('Checking location availability for edit...');
                const locationExists = await checkLocationExists(locationInput.value, deviceId);
                if (locationExists) {
                    showFieldError('edit_dev_location_error', 'This location is already in use by another device.');
                    showBanner('error', 'This location is already in use by another device.');
                    return; // Don't submit the form
                }
            }

            console.log('Edit validation passed, submitting form...');
            // If validation passes, submit the form
            this.submit();
        });
    }
});

// Simplified success message handler for device page
function handleSuccessMessages() {
    let successMessageShown = false;
    const existingAlert = document.querySelector('.success-alert');

    // If there's already a success alert showing, don't show another one
    if (existingAlert) {
        console.log('Success alert already showing, skipping duplicate');
        return;
    }

    console.log('Starting success message detection...');

    // 1. Check if there's a success message in the session (most reliable method)
    const sessionSuccessMessage = '{{ session("success") }}';
    console.log('Raw success message from session:', sessionSuccessMessage);

    if (sessionSuccessMessage && sessionSuccessMessage.trim() !== '' && !successMessageShown) {
        console.log('Processing success message from session:', sessionSuccessMessage);

        let displayMessage = 'Operation completed successfully.';
        if (sessionSuccessMessage.toLowerCase().includes('added') || sessionSuccessMessage.toLowerCase().includes('created')) {
            displayMessage = 'Device added successfully.';
        } else if (sessionSuccessMessage.toLowerCase().includes('updated') || sessionSuccessMessage.toLowerCase().includes('modified')) {
            displayMessage = 'Device updated successfully.';
        } else if (sessionSuccessMessage.toLowerCase().includes('deleted') || sessionSuccessMessage.toLowerCase().includes('removed')) {
            displayMessage = 'Device deleted successfully.';
        } else {
            // Use the original message if it doesn't match known patterns
            displayMessage = sessionSuccessMessage;
        }

        console.log('Showing session success message:', displayMessage);
        showSuccessAlert(displayMessage);
        successMessageShown = true;
    }

    // 2. Check for success message in URL parameters (for AJAX responses)
    const urlParams = new URLSearchParams(window.location.search);
    const urlSuccessMessage = urlParams.get('success');

    if (urlSuccessMessage && !successMessageShown) {
        console.log('Found success message in URL parameters:', urlSuccessMessage);
        showSuccessAlert(urlSuccessMessage);
        successMessageShown = true;

        // Clean up URL
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }

    console.log('Success message detection completed. Message shown:', successMessageShown);
}
// Load notifications immediately
loadNotifications();

// Set up polling every 30 seconds
setInterval(loadNotifications, 20000);

// Initialize success message handling
handleSuccessMessages();
</script>
@endsection

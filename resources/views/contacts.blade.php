@extends('layouts.app')

@section('page_heading', 'Contacts Information')
@section('title', 'Contacts Information')

@section('content')
<div class="container mx-auto px-4 py-6">
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

        .btn-add-contact {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
            transition: all 0.2s ease;
        }

        .btn-add-contact:hover {
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
            transform: translateY(-1px);
        }

        .btn-open-add {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border: 1px solid #28a745;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
            transition: all 0.2s ease;
        }

        .btn-open-add:hover {
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
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

        /* Error Banner and Alert Positioning from devices */
        .error-banner,
        .success-alert {
            position: fixed !important;
            z-index: 9999999 !important;
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
            right: 1rem !important;
            max-width: 400px;
        }

        /* Form field validation styles from devices */
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
    </style>

    <!-- Statistics Cards Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Contacts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $contacts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Active Contacts</p>
                    <p class="text-2xl font-bold text-green-600">{{ $contacts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Positions</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $contacts->pluck('position')->unique()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Left Section: Search + Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search Bar -->
                <div class="w-80">
                    <form method="GET" action="{{ route('contacts.index') }}" class="flex items-center" id="contactSearchForm">
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
                </div>
                
                <!-- Position Filter -->
                <form method="GET" action="{{ route('contacts.index') }}" class="flex items-center" id="positionFilterForm">
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                    <select id="contact_position_filter" name="position" onchange="handlePositionFilter()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-32">
                        <option value="">All Positions</option>
                        <option value="Captain" {{ request('position') === 'Captain' ? 'selected' : '' }}>Captain</option>
                        <option value="Co-Captain" {{ request('position') === 'Co-Captain' ? 'selected' : '' }}>Co-Captain</option>
                        <option value="Councilor" {{ request('position') === 'Councilor' ? 'selected' : '' }}>Councilor</option>
                        <option value="Secretary" {{ request('position') === 'Secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="Treasurer" {{ request('position') === 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                        <option value="Kagawad" {{ request('position') === 'Kagawad' ? 'selected' : '' }}>Kagawad</option>
                        <option value="Chairperson" {{ request('position') === 'Chairperson' ? 'selected' : '' }}>Chairperson</option>
                        <option value="Member" {{ request('position') === 'Member' ? 'selected' : '' }}>Member</option>
                    </select>
                </form>
            </div>
                    
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="openAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add New Contact
                </button>
                <button 
                    id="downloadContactsBtn"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>
    </div>
    
    <!-- Contacts Table Container -->
    <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full mt-6">
        <div class="px-5 py-3 rounded-t-xl bg-[#242F41] text-white">
            <h3 class="text-base font-medium flex items-center gap-2">
                <i class="fas fa-users w-4"></i>
                Contact Information
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table id="contacts-table" class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Contact ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Full Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Brgy. Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Contact Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Position</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 capitalized tracking-wider border-b border-gray-200">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $contact->contact_id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $contact->firstname }}
                                @if($contact->middlename)
                                    {{ $contact->middlename }}
                                @endif
                                {{ $contact->lastname }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $contact->brgy_location }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @php
                                    $contactNumbers = $contact->contact_numbers ? json_decode($contact->contact_numbers, true) : [];
                                @endphp
                                @if(!empty($contactNumbers))
                                    @foreach($contactNumbers as $index => $number)
                                        <div class="mb-1">{{ $number }}</div>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">No contact numbers</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($contact->position)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($contact->position === 'Captain') bg-green-100 text-green-800
                                        @elseif($contact->position === 'Kagawad') bg-purple-100 text-purple-800
                                        @elseif($contact->position === 'Co-Captain') bg-blue-100 text-blue-800
                                        @elseif($contact->position === 'Secretary') bg-yellow-100 text-yellow-800
                                        @elseif($contact->position === 'Treasurer') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $contact->position }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openEditModal('{{ $contact->contact_id }}', '{{ $contact->firstname }}', '{{ $contact->middlename }}', '{{ $contact->lastname }}', '{{ $contact->brgy_location }}', '{{ $contact->contact_numbers ?? json_encode([]) }}', '{{ $contact->position ?? '' }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 text-white rounded-full hover:bg-amber-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2 shadow-sm transition-colors"
                                        aria-label="Edit"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteContact('{{ $contact->contact_id }}', '{{ $contact->firstname }} {{ $contact->lastname }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 text-white rounded-full hover:bg-rose-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2 shadow-sm transition-colors"
                                        aria-label="Delete"
                                        title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603 .051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.527 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.951.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5 .058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48 .058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5 .058l.345-9z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No contacts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div>
                @php
                    $isPaginated = method_exists($contacts, 'firstItem');
                    $totalResults = $isPaginated && method_exists($contacts, 'total')
                        ? $contacts->total()
                        : (is_countable($contacts) ? count($contacts) : 0);
                    $firstItem = $isPaginated ? ($contacts->firstItem() ?? 0) : ($totalResults > 0 ? 1 : 0);
                    $lastItem  = $isPaginated ? ($contacts->lastItem() ?? 0)  : $totalResults;
                @endphp
            </div>
        </div>
        
        <!-- Pagination Controls -->
        @if($isPaginated && $contacts->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <!-- Mobile Pagination (Previous & Next only) -->
            <div class="flex-1 flex justify-between sm:hidden">
                @if($contacts->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $contacts->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if($contacts->hasMorePages())
                    <a href="{{ $contacts->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                        @if($contacts->onFirstPage())
                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed"><</span>
                        @else
                            <a href="{{ $contacts->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md"><</a>
                        @endif

                        <!-- Pagination Elements -->
                        @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                            @if ($page == $contacts->currentPage())
                                <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        @if($contacts->hasMorePages())
                            <a href="{{ $contacts->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">></a>
                        @else
                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">></span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Add Contact Modal -->
    <div id="addModal" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Add New Contact</h2>
                            <p class="text-blue-100 text-sm">Create new contact information</p>
                        </div>
                    </div>
                    <button onclick="closeAddModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="addContactForm" method="POST" action="{{ route('contacts.store') }}" novalidate>
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        First Name
                                        <span class="text-red-500">*</span>
                                    </span>
                                    <input id="add_first_name" name="first_name" type="text" class="form-input" placeholder="Enter first name" required>
                                    <div id="add_first_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                    <div id="add_first_name_success" class="success-message" style="display: none;"></div>
                                </label>
                            </div>

                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        Middle Name
                                    </span>
                                    <input id="add_middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-input" placeholder="Enter middle name (optional)">
                                    <div id="add_middle_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                    @if($errors->has('middle_name'))
                                        <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('middle_name') }}</div>
                                    @endif
                                </label>
                            </div>

                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        Last Name
                                        <span class="text-red-500">*</span>
                                    </span>
                                    <input id="add_last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="form-input" placeholder="Enter last name">
                                    <div id="add_last_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                    @if($errors->has('last_name'))
                                        <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                    Barangay Location
                                    <span class="text-red-500">*</span>
                                </span>
                                <div x-data="{ useDropdown: true, customLocationEnabled: false }">
                                    <!-- Location Dropdown -->
                                    <select 
                                        x-show="useDropdown" 
                                        id="add_brgy_location_select" 
                                        name="brgy_location" 
                                        @change="if ($event.target.value === 'Other') { 
                                            useDropdown = false; 
                                            customLocationEnabled = true; 
                                            $nextTick(() => { 
                                                const manualInput = document.getElementById('add_brgy_location_manual');
                                                if (manualInput) {
                                                    manualInput.focus(); 
                                                    manualInput.setAttribute('name', 'brgy_location');
                                                }
                                                $event.target.removeAttribute('name');
                                            }); 
                                        } else {
                                            $event.target.setAttribute('name', 'brgy_location');
                                            const manualInput = document.getElementById('add_brgy_location_manual');
                                            if (manualInput) {
                                                manualInput.removeAttribute('name');
                                            }
                                        }" 
                                        class="form-select w-full" 
                                        required>
                                        <option value="">Select Barangay Location</option>
                                        @foreach($availableLocations as $location)
                                            <option value="{{ $location }}" {{ old('brgy_location') === $location ? 'selected' : '' }}>{{ $location }}</option>
                                        @endforeach
                                        <option value="Other" {{ !$availableLocations->contains(old('brgy_location')) && old('brgy_location') ? 'selected' : '' }}>Other (Manual Entry)</option>
                                    </select>
                                    
                                    <!-- Manual Input Field -->
                                    <div x-show="!useDropdown" class="mt-2">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                x-show="!useDropdown"
                                                id="add_brgy_location_manual" 
                                                type="text" 
                                                value="{{ old('brgy_location') }}" 
                                                required 
                                                class="form-input flex-1" 
                                                placeholder="Enter barangay location manually">
                                            <button 
                                                type="button" 
                                                @click="useDropdown = true; 
                                                    customLocationEnabled = false; 
                                                    const manualInput = document.getElementById('add_brgy_location_manual');
                                                    const selectInput = document.getElementById('add_brgy_location_select');
                                                    if (manualInput) {
                                                        manualInput.value = ''; 
                                                        manualInput.removeAttribute('name');
                                                    }
                                                    if (selectInput) {
                                                        selectInput.value = '';
                                                        selectInput.setAttribute('name', 'brgy_location');
                                                    }" 
                                                class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2">
                                                <i class="fas fa-list"></i>
                                                <span class="hidden sm:inline">Back to List</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Error Message -->
                                <div id="add_brgy_location_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                @if($errors->has('brgy_location'))
                                    <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('brgy_location') }}</div>
                                @endif
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-phone text-blue-600"></i>
                                    Contact Numbers
                                    <span class="text-red-500">*</span>
                                </span>
                                <div id="contact-numbers-container">
                                    <div class="contact-number-field flex items-center gap-2 mb-2">
                                        <input id="add_contact_numbers_0" type="text" name="contact_numbers[]" value="{{ old('contact_numbers.0') }}" required class="form-input" placeholder="Enter contact number">
                                        <button type="button" onclick="removeContactNumber(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Error Message -->
                                <div id="add_contact_numbers_0_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                @if($errors->has('contact_numbers.0'))
                                    <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('contact_numbers.0') }}</div>
                                @endif
                                @if($errors->has('contact_numbers'))
                                    <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('contact_numbers') }}</div>
                                @endif
                            </label>
                        </div>
                        
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-briefcase text-blue-600"></i>
                                    Position
                                    <span class="text-red-500">*</span>
                                </span>
                                <select id="add_position" name="position" required class="form-select">
                                    <option value="" disabled selected>Select Position</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos }}" {{ old('position')===$pos ? 'selected' : '' }}>{{ $pos }}</option>
                                    @endforeach
                                </select>
                                <div id="add_position_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                @if($errors->has('position'))
                                    <div class="error-message text-red-600 text-sm mt-1">{{ $errors->first('position') }}</div>
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
                            Add Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div id="editModal" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#0a1d3a] to-[#1e3a8a] text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-edit text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Edit Contact</h2>
                            <p class="text-blue-100 text-sm">Update contact information</p>
                        </div>
                    </div>
                    <button onclick="closeEditModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="editForm" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        First Name
                                        <span class="text-red-500">*</span>
                                    </span>
                                    <input id="edit_first_name" name="first_name" type="text" class="form-input" placeholder="Enter first name" required>
                                    <div id="edit_first_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                    <div id="edit_first_name_success" class="success-message" style="display: none;"></div>
                                </label>
                            </div>

                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        Middle Name
                                    </span>
                                    <input id="edit_middle_name" name="middle_name" type="text" class="form-input" placeholder="Enter middle name (optional)">
                                    <div id="edit_middle_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                </label>
                            </div>

                            <div>
                                <label class="block">
                                    <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i>
                                        Last Name
                                        <span class="text-red-500">*</span>
                                    </span>
                                    <input id="edit_last_name" name="last_name" type="text" class="form-input" placeholder="Enter last name" required>
                                    <div id="edit_last_name_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                    Barangay Location
                                    <span class="text-red-500">*</span>
                                </span>
                                <div x-data="{ useDropdown: true, customLocationEnabled: false }">
                                    <!-- Location Dropdown -->
                                    <select 
                                        x-show="useDropdown" 
                                        id="edit_brgy_location_select" 
                                        name="brgy_location" 
                                        @change="if ($event.target.value === 'Other') { 
                                            useDropdown = false; 
                                            customLocationEnabled = true; 
                                            $nextTick(() => { 
                                                const manualInput = document.getElementById('edit_brgy_location_manual');
                                                if (manualInput) {
                                                    manualInput.focus(); 
                                                    manualInput.setAttribute('name', 'brgy_location');
                                                }
                                                $event.target.removeAttribute('name');
                                            }); 
                                        } else {
                                            $event.target.setAttribute('name', 'brgy_location');
                                            const manualInput = document.getElementById('edit_brgy_location_manual');
                                            if (manualInput) {
                                                manualInput.removeAttribute('name');
                                            }
                                        }" 
                                        class="form-select w-full" 
                                        required>
                                        <option value="">Select Barangay Location</option>
                                        @foreach($availableLocations as $location)
                                            <option value="{{ $location }}">{{ $location }}</option>
                                        @endforeach
                                        <option value="Other">Other (Manual Entry)</option>
                                    </select>
                                    
                                    <!-- Manual Input Field -->
                                    <div x-show="!useDropdown" class="mt-2">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                x-show="!useDropdown"
                                                id="edit_brgy_location_manual" 
                                                type="text" 
                                                required 
                                                class="form-input flex-1" 
                                                placeholder="Enter barangay location manually">
                                            <button 
                                                type="button" 
                                                @click="useDropdown = true; 
                                                    customLocationEnabled = false; 
                                                    const manualInput = document.getElementById('edit_brgy_location_manual');
                                                    const selectInput = document.getElementById('edit_brgy_location_select');
                                                    if (manualInput) {
                                                        manualInput.value = ''; 
                                                        manualInput.removeAttribute('name');
                                                    }
                                                    if (selectInput) {
                                                        selectInput.value = '';
                                                        selectInput.setAttribute('name', 'brgy_location');
                                                    }" 
                                                class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2">
                                                <i class="fas fa-list"></i>
                                                <span class="hidden sm:inline">Back to List</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Error Message -->
                                <div id="edit_brgy_location_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-phone text-blue-600"></i>
                                    Contact Numbers
                                    <span class="text-red-500">*</span>
                                </span>
                                <div id="edit-contact-numbers-container">
                                    <div class="contact-number-field flex items-center gap-2 mb-2">
                                        <input type="text" name="contact_numbers[]" required class="form-input" placeholder="Enter contact number">
                                        <button type="button" onclick="removeEditContactNumber(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Error Message -->
                                <div id="edit_contact_numbers_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                            </label>
                        </div>
                        
                        <div>
                            <label class="block">
                                <span class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-briefcase text-blue-600"></i>
                                    Position
                                    <span class="text-red-500">*</span>
                                </span>
                                <select id="edit_position" name="position" required class="form-select">
                                    <option value="" disabled selected>Select Position</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos }}">{{ $pos }}</option>
                                    @endforeach
                                </select>
                                <div id="edit_position_error" class="error-message text-red-600 text-sm mt-1" style="display: none;"></div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="button" @click="showModal = false" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Consolidated DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize notification polling
            initializeNotificationPolling();

            // Initialize contact search functionality
            initializeContactSearch();

            // Check if there's a success message in the session
            const successMessage = '{{ session("success") }}';
            if (successMessage) {
                if (successMessage.includes('added')) {
                    showSuccessAlert('');
                } else if (successMessage.includes('updated')) {
                    showSuccessAlert('Contact updated successfully.');
                } else if (successMessage.includes('deleted')) {
                    showSuccessAlert('Contact deleted successfully.');
                } else {
                    showSuccessAlert(successMessage);
                }
            }

            // Add form submission handler for the add contact form
            const addForm = document.querySelector('#addContactForm');
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Handle location submission before validation
                    handleLocationSubmission();

                    // Clear any existing errors first
                    clearAllFieldErrors();

                    // Client-side validation
                    const validation = validateContactForm();

                    if (validation.hasErrors) {
                        // Show general error message using unified banner system
                        if (typeof showBanner === 'function') {
                            showBanner('error', 'Please fix the validation errors before submitting.', 1500);
                        } else {
                            // Fallback to original implementation
                            showErrorBanner('Please fix the validation errors before submitting.');
                        }

                        return; // Don't submit the form
                    }

                    // Sanitize inputs
                    const firstNameInput = document.getElementById('add_first_name');
                    const middleNameInput = document.getElementById('add_middle_name');
                    const lastNameInput = document.getElementById('add_last_name');
                    const locationSelect = document.getElementById('add_brgy_location_select');
                    const locationManual = document.getElementById('add_brgy_location_manual');
                    const positionInput = document.getElementById('add_position');

                    if (firstNameInput) {
                        firstNameInput.value = sanitizeInput(firstNameInput.value.trim());
                    }

                    if (middleNameInput) {
                        middleNameInput.value = sanitizeInput(middleNameInput.value.trim());
                    }

                    if (lastNameInput) {
                        lastNameInput.value = sanitizeInput(lastNameInput.value.trim());
                    }

                    if (locationSelect) {
                        locationSelect.value = sanitizeInput(locationSelect.value.trim());
                    }

                    if (locationManual) {
                        locationManual.value = sanitizeInput(locationManual.value.trim());
                    }

                    if (positionInput) {
                        positionInput.value = sanitizeInput(positionInput.value.trim());
                    }

                    // Submit the form directly - the success message will be handled by the server response
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

            // Handle form submission for edit modal
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Handle location submission before validation
                handleLocationSubmission();

                // Client-side validation
                const { errors, hasErrors } = validateEditContactForm();
                
                if (hasErrors) {
                    // Show fixed error banner at top of page
                    FormValidator.showErrorBanner('editForm', 'Please fill in all required fields before submitting');

                    // Display validation errors
                    Object.keys(errors).forEach(fieldId => {
                        FormValidator.showFieldError(fieldId, errors[fieldId]);
                    });

                    return;
                }

                // If validation passes, submit the form
                const formData = new FormData(this);
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<div class="flex items-center"><div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Updating...</div>';
                submitBtn.disabled = true;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(JSON.stringify(errorData));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success toast notification
                        showSuccessAlert(data.message || 'Contact updated successfully.');

                        // Create notification for updated contact
                        createContactNotification('update', data.contact_name || 'Contact', data.location || 'Unknown Location');

                        // Success - close modal and reload
                        closeEditModal();
                        this.reset();
                        clearAllFieldErrors();
                        window.location.reload();
                    } else {
                        // Display server-side validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(fieldName => {
                                // Map field names to error element IDs
                                let fieldId = 'edit_' + fieldName;
                                if (fieldName === 'contact_numbers') {
                                    fieldId = 'edit_contact_numbers'; // Show error on contact numbers container
                                }
                                FormValidator.showFieldError(fieldId, data.errors[fieldName][0]);
                            });
                        } else {
                            alert('Error: ' + (data.message || 'Failed to update contact'));
                        }
                    }
                })
                .catch(error => {
                    try {
                        const errorData = JSON.parse(error.message);
                        if (errorData.errors) {
                            Object.keys(errorData.errors).forEach(fieldName => {
                                // Map field names to error element IDs
                                let fieldId = 'edit_' + fieldName;
                                if (fieldName === 'contact_numbers') {
                                    fieldId = 'edit_contact_numbers'; // Show error on contact numbers container
                                }
                                FormValidator.showFieldError(fieldId, errorData.errors[fieldName][0]);
                            });
                        } else {
                            alert('Error: ' + (errorData.message || 'Failed to update contact'));
                        }
                    } catch (e) {
                        console.error('Error:', error);
                        alert('An error occurred while updating the contact.');
                    }
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });

            // Download functionality
            const downloadBtn = document.getElementById('downloadContactsBtn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function() {
                    // Get current search and filter parameters
                    const searchParams = new URLSearchParams();
                    
                    // Get search parameter
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput && searchInput.value.trim()) {
                        searchParams.append('search', searchInput.value.trim());
                    }
                    
                    // Get position filter parameter
                    const positionSelect = document.querySelector('select[name="position"]');
                    if (positionSelect && positionSelect.value) {
                        searchParams.append('position', positionSelect.value);
                    }
                    
                    // Construct URL with parameters
                    let url = '{{ route("contacts.export_pdf") }}';
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

            // Real-time validation for add contact form
            setupAddFormValidation();

            // Real-time validation for edit contact form
            setupEditFormValidation();
        });

        // Function to validate contact form (Add Contact)
        function validateContactForm() {
            const errors = {};
            let hasErrors = false;

            // Clear previous errors
            clearFieldError('add_first_name_error');
            clearFieldError('add_middle_name_error');
            clearFieldError('add_last_name_error');
            clearFieldError('add_brgy_location_error');
            clearFieldError('add_contact_numbers_0_error');
            clearFieldError('add_position_error');

            // Get form elements
            const firstNameInput = document.getElementById('add_first_name');
            const middleNameInput = document.getElementById('add_middle_name');
            const lastNameInput = document.getElementById('add_last_name');
            const locationSelect = document.getElementById('add_brgy_location_select');
            const locationManual = document.getElementById('add_brgy_location_manual');
            const contactNumberFields = document.querySelectorAll('input[name="contact_numbers[]"]');
            const positionInput = document.getElementById('add_position');

            // First name validation
            const firstName = firstNameInput.value.trim();

            if (!firstName) {
                errors.add_first_name_error = 'This field is required.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('add_first_name_error', 'This field is required.');
            } else if (firstName.length < 2) {
                errors.add_first_name_error = 'First name must be at least 2 characters long.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('add_first_name_error', 'First name must be at least 2 characters long.');
            } else if (firstName.length > 50) {
                errors.add_first_name_error = 'First name must not exceed 50 characters.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('add_first_name_error', 'First name must not exceed 50 characters.');
            } else if (!/^[A-Za-z\s\-\']+$/.test(firstName)) {
                errors.add_first_name_error = 'First name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('add_first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                firstNameInput.classList.remove('form-field-invalid');
            }

            // Last name validation
            const lastName = lastNameInput.value.trim();

            if (!lastName) {
                errors.add_last_name_error = 'This field is required.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('add_last_name_error', 'This field is required.');
            } else if (lastName.length < 2) {
                errors.add_last_name_error = 'Last name must be at least 2 characters long.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('add_last_name_error', 'Last name must be at least 2 characters long.');
            } else if (lastName.length > 50) {
                errors.add_last_name_error = 'Last name must not exceed 50 characters.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('add_last_name_error', 'Last name must not exceed 50 characters.');
            } else if (!/^[A-Za-z\s\-\']+$/.test(lastName)) {
                errors.add_last_name_error = 'Last name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('add_last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                lastNameInput.classList.remove('form-field-invalid');
            }

            // Barangay location validation
            let brgyLocation = '';
            // Get value from either dropdown or manual input based on Alpine.js visibility
            if (locationSelect && locationSelect.style.display !== 'none' && !locationSelect.hasAttribute('x-show') || 
                (locationSelect.hasAttribute('x-show') && locationSelect.offsetParent !== null)) {
                // Dropdown is visible
                brgyLocation = locationSelect.value.trim();
            } else if (locationManual && locationManual.style.display !== 'none' && locationManual.offsetParent !== null) {
                // Manual input is visible
                brgyLocation = locationManual.value.trim();
            } else {
                // Fallback: check both fields
                brgyLocation = (locationSelect?.value || locationManual?.value || '').trim();
            }

            if (!brgyLocation || brgyLocation === 'Other') {
                errors.add_brgy_location_error = 'This field is required.';
                hasErrors = true;
                // Add red border for empty field
                if (locationSelect && locationSelect.offsetParent !== null) {
                    locationSelect.classList.add('form-field-invalid');
                } else if (locationManual && locationManual.offsetParent !== null) {
                    locationManual.classList.add('form-field-invalid');
                }
                showFieldError('add_brgy_location_error', 'This field is required.');
            } else {
                // Valid format - remove error styling
                if (locationSelect) locationSelect.classList.remove('form-field-invalid');
                if (locationManual) locationManual.classList.remove('form-field-invalid');
            }

            // Contact numbers validation
            let hasValidContactNumber = false;
            let firstContactField = null;

            contactNumberFields.forEach((field, index) => {
                const value = field.value.trim();
                if (value) {
                    hasValidContactNumber = true;
                    firstContactField = firstContactField || field;
                    // Validate contact number format
                    const cleanNumber = value.replace(/\D/g, '');
                    if (cleanNumber.length < 11 || cleanNumber.length > 12) {
                        errors[`add_contact_numbers_${index}_error`] = 'Contact number must be 11 or 12 digits.';
                        hasErrors = true;
                        field.classList.add('form-field-invalid');
                        showFieldError(`add_contact_numbers_${index}_error`, 'Contact number must be 11 or 12 digits.');
                    } else if (!/^(09|\+639)\d{9}$/.test(value.replace(/\s+/g, ''))) {
                        errors[`add_contact_numbers_${index}_error`] = 'Please enter a valid Philippine mobile number (e.g., 09123456789).';
                        hasErrors = true;
                        field.classList.add('form-field-invalid');
                        showFieldError(`add_contact_numbers_${index}_error`, 'Please enter a valid Philippine mobile number (e.g., 09123456789).');
                    } else {
                        // Valid format - remove error styling
                        field.classList.remove('form-field-invalid');
                    }
                }
            });

            // At least one contact number is required
            if (!hasValidContactNumber) {
                errors.add_contact_numbers_0_error = 'This field is required.';
                hasErrors = true;
                // Add red border for empty field (first contact number field)
                if (contactNumberFields.length > 0) {
                    contactNumberFields[0].classList.add('form-field-invalid');
                }
                showFieldError('add_contact_numbers_0_error', 'This field is required.');
            }

            // Position validation
            const position = positionInput.value;

            if (!position || position === '') {
                errors.add_position_error = 'This field is required.';
                hasErrors = true;
                positionInput.classList.add('form-field-invalid');
                showFieldError('add_position_error', 'This field is required.');
            } else {
                // Valid format - remove error styling
                positionInput.classList.remove('form-field-invalid');
            }

            // Middle name validation (optional but if provided should be valid)
            const middleName = middleNameInput.value.trim();
            if (middleName && !/^[A-Za-z\s\-\']+$/.test(middleName)) {
                errors.add_middle_name_error = 'Middle name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                middleNameInput.classList.add('form-field-invalid');
                showFieldError('add_middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                middleNameInput.classList.remove('form-field-invalid');
            }

            return { errors, hasErrors };
        }

        // Function to validate edit contact form
        function validateEditContactForm() {
            const errors = {};
            let hasErrors = false;

            // Clear previous errors
            clearFieldError('edit_first_name_error');
            clearFieldError('edit_middle_name_error');
            clearFieldError('edit_last_name_error');
            clearFieldError('edit_brgy_location_error');
            clearFieldError('edit_contact_numbers_error');
            clearFieldError('edit_position_error');

            // Get form elements
            const firstNameInput = document.getElementById('edit_first_name');
            const middleNameInput = document.getElementById('edit_middle_name');
            const lastNameInput = document.getElementById('edit_last_name');
            const locationSelect = document.getElementById('edit_brgy_location_select');
            const locationManual = document.getElementById('edit_brgy_location_manual');
            const contactNumberFields = document.querySelectorAll('#edit-contact-numbers-container input[name="contact_numbers[]"]');
            const positionInput = document.getElementById('edit_position');

            if (!firstNameInput || !lastNameInput || !locationSelect || !locationManual || !positionInput) {
                errors.general = 'Form elements not found. Please refresh the page.';
                hasErrors = true;
                return { errors, hasErrors };
            }

            // First name validation
            const firstName = firstNameInput.value.trim();
            if (!firstName) {
                errors.edit_first_name_error = 'This field is required.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('edit_first_name_error', 'This field is required.');
            } else if (firstName.length < 2) {
                errors.edit_first_name_error = 'First name must be at least 2 characters long.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('edit_first_name_error', 'First name must be at least 2 characters long.');
            } else if (firstName.length > 50) {
                errors.edit_first_name_error = 'First name must not exceed 50 characters.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('edit_first_name_error', 'First name must not exceed 50 characters.');
            } else if (!/^[A-Za-z\s\-\']+$/.test(firstName)) {
                errors.edit_first_name_error = 'First name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                firstNameInput.classList.add('form-field-invalid');
                showFieldError('edit_first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                firstNameInput.classList.remove('form-field-invalid');
            }

            // Last name validation
            const lastName = lastNameInput.value.trim();
            if (!lastName) {
                errors.edit_last_name_error = 'This field is required.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('edit_last_name_error', 'This field is required.');
            } else if (lastName.length < 2) {
                errors.edit_last_name_error = 'Last name must be at least 2 characters long.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('edit_last_name_error', 'Last name must be at least 2 characters long.');
            } else if (lastName.length > 50) {
                errors.edit_last_name_error = 'Last name must not exceed 50 characters.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('edit_last_name_error', 'Last name must not exceed 50 characters.');
            } else if (!/^[A-Za-z\s\-\']+$/.test(lastName)) {
                errors.edit_last_name_error = 'Last name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                lastNameInput.classList.add('form-field-invalid');
                showFieldError('edit_last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                lastNameInput.classList.remove('form-field-invalid');
            }

            // Barangay location validation
            let brgyLocation = '';
            // Get value from either dropdown or manual input
            if (locationSelect && locationSelect.style.display !== 'none' && locationSelect.offsetParent !== null) {
                brgyLocation = locationSelect.value.trim();
            } else if (locationManual && locationManual.style.display !== 'none' && locationManual.offsetParent !== null) {
                brgyLocation = locationManual.value.trim();
            }

            if (!brgyLocation || brgyLocation === 'Other') {
                errors.edit_brgy_location_error = 'This field is required.';
                hasErrors = true;
                // Add red border for empty field
                if (locationSelect && locationSelect.offsetParent !== null) {
                    locationSelect.classList.add('form-field-invalid');
                } else if (locationManual && locationManual.offsetParent !== null) {
                    locationManual.classList.add('form-field-invalid');
                }
                showFieldError('edit_brgy_location_error', 'This field is required.');
            } else {
                // Valid format - remove error styling
                if (locationSelect) locationSelect.classList.remove('form-field-invalid');
                if (locationManual) locationManual.classList.remove('form-field-invalid');
            }

            // Contact numbers validation
            let hasValidContactNumber = false;

            contactNumberFields.forEach((field, index) => {
                const value = field.value.trim();
                if (value) {
                    hasValidContactNumber = true;
                    // Validate contact number format
                    const cleanNumber = value.replace(/\D/g, '');
                    if (cleanNumber.length < 11 || cleanNumber.length > 12) {
                        errors[`edit_contact_num_${index}_error`] = 'Contact number must be 11 or 12 digits.';
                        hasErrors = true;
                        field.classList.add('form-field-invalid');
                        showFieldError(`edit_contact_num_${index}_error`, 'Contact number must be 11 or 12 digits.');
                    } else if (!/^(09|\+639)\d{9}$/.test(value.replace(/\s+/g, ''))) {
                        errors[`edit_contact_num_${index}_error`] = 'Please enter a valid Philippine mobile number (e.g., 09123456789).';
                        hasErrors = true;
                        field.classList.add('form-field-invalid');
                        showFieldError(`edit_contact_num_${index}_error`, 'Please enter a valid Philippine mobile number (e.g., 09123456789).');
                    } else {
                        // Valid format - remove error styling
                        field.classList.remove('form-field-invalid');
                    }
                }
            });

            // At least one contact number is required
            if (!hasValidContactNumber) {
                errors.edit_contact_numbers_error = 'This field is required.';
                hasErrors = true;
                // Add red border for empty field (first contact number field)
                if (contactNumberFields.length > 0) {
                    contactNumberFields[0].classList.add('form-field-invalid');
                }
                showFieldError('edit_contact_numbers_error', 'This field is required.');
            }

            // Position validation
            const position = positionInput.value;
            if (!position || position === '') {
                errors.edit_position_error = 'This field is required.';
                hasErrors = true;
                positionInput.classList.add('form-field-invalid');
                showFieldError('edit_position_error', 'This field is required.');
            } else {
                // Valid format - remove error styling
                positionInput.classList.remove('form-field-invalid');
            }

            // Middle name validation (optional but if provided should be valid)
            const middleName = middleNameInput.value.trim();
            if (middleName && !/^[A-Za-z\s\-\']+$/.test(middleName)) {
                errors.edit_middle_name_error = 'Middle name may only contain letters, spaces, hyphens, and apostrophes.';
                hasErrors = true;
                middleNameInput.classList.add('form-field-invalid');
                showFieldError('edit_middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
            } else {
                // Valid format - remove error styling
                middleNameInput.classList.remove('form-field-invalid');
            }

            return { errors, hasErrors };
        }

        // Real-time validation setup for add form
        function setupAddFormValidation() {
            // Real-time validation for add contact form
            const firstNameInput = document.getElementById('add_first_name');
            const middleNameInput = document.getElementById('add_middle_name');
            const lastNameInput = document.getElementById('add_last_name');
            const locationSelect = document.getElementById('add_brgy_location_select');
            const locationManual = document.getElementById('add_brgy_location_manual');
            const positionInput = document.getElementById('add_position');
            const contactNumberContainer = document.getElementById('contact-numbers-container');

            // Add real-time validation for first name
            if (firstNameInput) {
                firstNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('add_first_name_error');
                    clearFieldSuccess('add_first_name');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('add_first_name_error', 'First name is required.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length < 2) {
                        showFieldError('add_first_name_error', 'First name must be at least 2 characters long.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length > 50) {
                        showFieldError('add_first_name_error', 'First name must not exceed 50 characters.');
                        this.classList.add('form-field-invalid');
                    } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('add_first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('add_first_name_error');
                        showFieldSuccess('add_first_name');
                    }
                });
            }

            // Add real-time validation for middle name
            if (middleNameInput) {
                middleNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('add_middle_name_error');
                    clearFieldSuccess('add_middle_name');
                    
                    // Real-time validation
                    if (value && !/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('add_middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
                    } else if (value) {
                        clearFieldError('add_middle_name_error');
                        showFieldSuccess('add_middle_name', 'Valid middle name');
                    } else {
                        // Clear validation for empty optional field
                        clearFieldError('add_middle_name_error');
                        clearFieldSuccess('add_middle_name');
                    }
                });
            }

            // Add real-time validation for last name
            if (lastNameInput) {
                lastNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('add_last_name_error');
                    clearFieldSuccess('add_last_name');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('add_last_name_error', 'Last name is required.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length < 2) {
                        showFieldError('add_last_name_error', 'Last name must be at least 2 characters long.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length > 50) {
                        showFieldError('add_last_name_error', 'Last name must not exceed 50 characters.');
                        this.classList.add('form-field-invalid');
                    } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('add_last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('add_last_name_error');
                        showFieldSuccess('add_last_name');
                    }
                });
                
                // Add blur event to check for empty field
                lastNameInput.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError('add_last_name_error', 'Last name is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for location (dropdown)
            if (locationSelect) {
                locationSelect.addEventListener('change', function() {
                    const value = this.value.trim();
                    clearFieldError('add_brgy_location_error');
                    clearFieldSuccess('add_brgy_location_select');
                    
                    // Real-time validation
                    if (!value || value === 'Other') {
                        showFieldError('add_brgy_location_error', 'Barangay location is required.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('add_brgy_location_error');
                        showFieldSuccess('add_brgy_location_select', 'Location selected');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                locationSelect.addEventListener('blur', function() {
                    if (!this.value.trim() || this.value === 'Other') {
                        showFieldError('add_brgy_location_error', 'Barangay location is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for location (manual input)
            if (locationManual) {
                let locationCheckTimeout;
                locationManual.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('add_brgy_location_error');
                    clearFieldSuccess('add_brgy_location_manual');
                    
                    // Clear previous timeout
                    if (locationCheckTimeout) {
                        clearTimeout(locationCheckTimeout);
                    }
                    
                    // Real-time validation
                    if (value && value.length < 2) {
                        showFieldError('add_brgy_location_error', 'Location must be at least 2 characters.');
                    } else if (value && value.length > 100) {
                        showFieldError('add_brgy_location_error', 'Location must not exceed 100 characters.');
                    } else if (value) {
                        // Valid format - show success
                        clearFieldError('add_brgy_location_error');
                        showFieldSuccess('add_brgy_location_manual', 'Valid location');

                        // Check for duplicate location after user stops typing (debounced)
                        locationCheckTimeout = setTimeout(async () => {
                            const locationExists = await checkLocationExists(value);
                            if (locationExists) {
                                showFieldError('add_brgy_location_error', 'This location is already in use.');
                                clearFieldSuccess('add_brgy_location_manual');
                            } else {
                                showFieldSuccess('add_brgy_location_manual', 'Location is available');
                            }
                        }, 500); // 500ms delay
                    } else {
                        // Clear validation for empty field
                        clearFieldError('add_brgy_location_error');
                        clearFieldSuccess('add_brgy_location_manual');
                    }
                });
            }

            // Add real-time validation for position
            if (positionInput) {
                positionInput.addEventListener('change', function() {
                    const value = this.value;
                    clearFieldError('add_position_error');
                    clearFieldSuccess('add_position');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('add_position_error', 'Position is required.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('add_position_error');
                        showFieldSuccess('add_position', 'Position selected');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                positionInput.addEventListener('blur', function() {
                    if (!this.value) {
                        showFieldError('add_position_error', 'Position is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for contact numbers
            if (contactNumberContainer) {
                // Use event delegation for dynamically added contact number fields
                contactNumberContainer.addEventListener('input', function(e) {
                    if (e.target.name === 'contact_numbers[]') {
                        const field = e.target;
                        const value = field.value.trim();
                        const fieldId = field.id || 'add_contact_numbers_0';
                        const errorId = fieldId + '_error';
                        
                        clearFieldError(errorId);
                        clearFieldSuccess(fieldId);
                        
                        // Real-time validation
                        if (!value) {
                            // Optional field, no error for empty values
                            clearFieldError(errorId);
                            clearFieldSuccess(fieldId);
                            // Remove red border for empty optional field
                            field.classList.remove('form-field-invalid');
                        } else {
                            // Check if value contains only numeric characters, spaces, hyphens, plus sign
                            if (!/^[\d\s\-\+]*$/.test(value)) {
                                showFieldError(errorId, 'Contact number can only contain numeric characters, spaces, hyphens, and plus sign.');
                                field.classList.add('form-field-invalid');
                            } else {
                                // Validate contact number format
                                const cleanNumber = value.replace(/\D/g, '');
                                if (cleanNumber.length < 11 || cleanNumber.length > 12) {
                                    showFieldError(errorId, 'Contact number must be 11 or 12 digits.');
                                    field.classList.add('form-field-invalid');
                                } else if (!/^(09|\+639)\d{9}$/.test(value.replace(/\s+/g, ''))) {
                                    showFieldError(errorId, 'Please enter a valid Philippine mobile number (e.g., 09123456789).');
                                    field.classList.add('form-field-invalid');
                                } else {
                                    // Valid format - show success
                                    clearFieldError(errorId);
                                    showFieldSuccess(fieldId, 'Valid contact number');
                                    field.classList.remove('form-field-invalid');
                                }
                            }
                        }
                    }
                });
                
                // Add blur event to check for empty field (only for the first contact number which is required)
                contactNumberContainer.addEventListener('blur', function(e) {
                    if (e.target.name === 'contact_numbers[]') {
                        const field = e.target;
                        const value = field.value.trim();
                        const fieldId = field.id || 'add_contact_numbers_0';
                        const errorId = fieldId + '_error';
                        
                        // Only apply this to the first contact number field (required field)
                        if (fieldId === 'add_contact_numbers_0' && !value) {
                            showFieldError(errorId, 'At least one contact number is required.');
                            field.classList.add('form-field-invalid');
                        }
                    }
                }, true); // Use capture phase to catch blur events properly
            }
        }

        // Real-time validation setup for edit form
        function setupEditFormValidation() {
            // Real-time validation for edit contact form
            const editFirstNameInput = document.getElementById('edit_first_name');
            const editMiddleNameInput = document.getElementById('edit_middle_name');
            const editLastNameInput = document.getElementById('edit_last_name');
            const editLocationSelect = document.getElementById('edit_brgy_location_select');
            const editLocationManual = document.getElementById('edit_brgy_location_manual');
            const editPositionInput = document.getElementById('edit_position');
            const editContactNumberContainer = document.getElementById('edit-contact-numbers-container');

            // Add real-time validation for first name (edit form)
            if (editFirstNameInput) {
                editFirstNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('edit_first_name_error');
                    clearFieldSuccess('edit_first_name');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('edit_first_name_error', 'First name is required.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length < 2) {
                        showFieldError('edit_first_name_error', 'First name must be at least 2 characters long.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length > 50) {
                        showFieldError('edit_first_name_error', 'First name must not exceed 50 characters.');
                        this.classList.add('form-field-invalid');
                    } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('edit_first_name_error', 'First name may only contain letters, spaces, hyphens, and apostrophes.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('edit_first_name_error');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                editFirstNameInput.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError('edit_first_name_error', 'First name is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for middle name (edit form)
            if (editMiddleNameInput) {
                editMiddleNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('edit_middle_name_error');
                    clearFieldSuccess('edit_middle_name');
                    
                    // Real-time validation
                    if (value && !/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('edit_middle_name_error', 'Middle name may only contain letters, spaces, hyphens, and apostrophes.');
                    } else if (value) {
                        clearFieldError('edit_middle_name_error');
                        showFieldSuccess('edit_middle_name', 'Valid middle name');
                    } else {
                        // Clear validation for empty optional field
                        clearFieldError('edit_middle_name_error');
                        clearFieldSuccess('edit_middle_name');
                    }
                });
            }

            // Add real-time validation for last name (edit form)
            if (editLastNameInput) {
                editLastNameInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('edit_last_name_error');
                    clearFieldSuccess('edit_last_name');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('edit_last_name_error', 'Last name is required.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length < 2) {
                        showFieldError('edit_last_name_error', 'Last name must be at least 2 characters long.');
                        this.classList.add('form-field-invalid');
                    } else if (value.length > 50) {
                        showFieldError('edit_last_name_error', 'Last name must not exceed 50 characters.');
                        this.classList.add('form-field-invalid');
                    } else if (!/^[A-Za-z\s\-\']+$/.test(value)) {
                        showFieldError('edit_last_name_error', 'Last name may only contain letters, spaces, hyphens, and apostrophes.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('edit_last_name_error');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                editLastNameInput.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError('edit_last_name_error', 'Last name is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for location (dropdown) - edit form
            if (editLocationSelect) {
                editLocationSelect.addEventListener('change', function() {
                    const value = this.value.trim();
                    clearFieldError('edit_brgy_location_error');
                    clearFieldSuccess('edit_brgy_location_select');
                    
                    // Real-time validation
                    if (!value || value === 'Other') {
                        showFieldError('edit_brgy_location_error', 'Barangay location is required.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('edit_brgy_location_error');
                        showFieldSuccess('edit_brgy_location_select', 'Location selected');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                editLocationSelect.addEventListener('blur', function() {
                    if (!this.value.trim() || this.value === 'Other') {
                        showFieldError('edit_brgy_location_error', 'Barangay location is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for location (manual input) - edit form
            if (editLocationManual) {
                editLocationManual.addEventListener('input', function() {
                    const value = this.value.trim();
                    clearFieldError('edit_brgy_location_error');
                    clearFieldSuccess('edit_brgy_location_manual');
                    
                    // Real-time validation
                    if (value && value.length < 2) {
                        showFieldError('edit_brgy_location_error', 'Location must be at least 2 characters.');
                    } else if (value && value.length > 100) {
                        showFieldError('edit_brgy_location_error', 'Location must not exceed 100 characters.');
                    } else if (value) {
                        // Valid format - show success
                        clearFieldError('edit_brgy_location_error');
                        showFieldSuccess('edit_brgy_location_manual', 'Valid location');
                    } else {
                        // Clear validation for empty field
                        clearFieldError('edit_brgy_location_error');
                        clearFieldSuccess('edit_brgy_location_manual');
                    }
                });
            }

            // Add real-time validation for position (edit form)
            if (editPositionInput) {
                editPositionInput.addEventListener('change', function() {
                    const value = this.value;
                    clearFieldError('edit_position_error');
                    clearFieldSuccess('edit_position');
                    
                    // Real-time validation
                    if (!value) {
                        showFieldError('edit_position_error', 'Position is required.');
                        this.classList.add('form-field-invalid');
                    } else {
                        // Valid format - show success
                        clearFieldError('edit_position_error');
                        showFieldSuccess('edit_position', 'Position selected');
                        this.classList.remove('form-field-invalid');
                    }
                });
                
                // Add blur event to check for empty field
                editPositionInput.addEventListener('blur', function() {
                    if (!this.value) {
                        showFieldError('edit_position_error', 'Position is required.');
                        this.classList.add('form-field-invalid');
                    }
                });
            }

            // Add real-time validation for contact numbers (edit form)
            if (editContactNumberContainer) {
                // Use event delegation for dynamically added contact number fields
                editContactNumberContainer.addEventListener('input', function(e) {
                    if (e.target.name === 'contact_numbers[]') {
                        const field = e.target;
                        const value = field.value.trim();
                        const fieldId = field.id || 'edit_contact_numbers_0';
                        const errorId = fieldId + '_error';
                        
                        clearFieldError(errorId);
                        clearFieldSuccess(fieldId);
                        
                        // Real-time validation
                        if (!value) {
                            // Optional field, no error for empty values
                            clearFieldError(errorId);
                            clearFieldSuccess(fieldId);
                            // Remove red border for empty optional field
                            field.classList.remove('form-field-invalid');
                        } else {
                            // Check if value contains only numeric characters, spaces, hyphens, plus sign
                            if (!/^[\d\s\-\+]*$/.test(value)) {
                                showFieldError(errorId, 'Contact number can only contain numeric characters, spaces, hyphens, and plus sign.');
                                field.classList.add('form-field-invalid');
                            } else {
                                // Validate contact number format
                                const cleanNumber = value.replace(/\D/g, '');
                                if (cleanNumber.length < 11 || cleanNumber.length > 12) {
                                    showFieldError(errorId, 'Contact number must be 11 or 12 digits.');
                                    field.classList.add('form-field-invalid');
                                } else if (!/^(09|\+639)\d{9}$/.test(value.replace(/\s+/g, ''))) {
                                    showFieldError(errorId, 'Please enter a valid Philippine mobile number (e.g., 09123456789).');
                                    field.classList.add('form-field-invalid');
                                } else {
                                    // Valid format - show success
                                    clearFieldError(errorId);
                                    showFieldSuccess(fieldId, 'Valid contact number');
                                    field.classList.remove('form-field-invalid');
                                }
                            }
                        }
                    }
                });
            }
        }

        // Simplified error handling - use global FormValidator
        function clearAllFieldErrors() {
            // Clear global fixed error banner
            FormValidator.hideErrorBanner('addContactForm');
            FormValidator.hideErrorBanner('editForm');

            // Clear all field errors
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.style.display = 'none';
                element.textContent = '';
            });

            // Remove red borders from all form fields
            const addForm = document.getElementById('addContactForm');
            const editForm = document.getElementById('editForm');
            
            if (addForm) {
                const addFormFields = addForm.querySelectorAll('.form-input, .form-select');
                addFormFields.forEach(field => {
                    field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
                });
            }
            
            if (editForm) {
                const editFormFields = editForm.querySelectorAll('.form-input, .form-select');
                editFormFields.forEach(field => {
                    field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
                });
            }
        }

        // Function to show success alert
        function showSuccessAlert(message) {
            // Remove any existing alerts
            const existingAlert = document.querySelector('.success-alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Ensure the message is not empty
            if (!message || message.trim() === '') {
                return;
            }

            // Use the unified banner system from app.blade.php when available
            if (typeof showBanner === 'function') {
                // Map message types to banner types
                let bannerType = 'success';
                if (message.toLowerCase().includes('deleted') || message.toLowerCase().includes('delete')) {
                    bannerType = 'error'; // For delete operations, we'll still use success styling but with delete icon
                }
                
                // Show unified banner
                showBanner(bannerType, message, 5000); // 5 second duration
                return;
            }

            // Fallback to original implementation if showBanner is not available
            // Determine styling based on message type
            let bgColor, borderColor, textColor, iconClass;
            if (message.toLowerCase().includes('deleted') || message.toLowerCase().includes('delete')) {
                // Red styling for delete operations
                bgColor = 'bg-red-100';
                borderColor = 'border-red-400';
                textColor = 'text-red-700';
                iconClass = 'fas fa-trash-alt';
            } else {
                // Green styling for add/update operations
                bgColor = 'bg-green-100';
                borderColor = 'border-green-400';
                textColor = 'text-green-700';
                iconClass = 'fas fa-check-circle';
            }

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

        // Function to show error banner - using unified banner system
        function showErrorBanner(message = 'An error occurred. Please try again.') {
            // Use the unified banner system from app.blade.php
            if (typeof showBanner === 'function') {
                showBanner('error', message, 1500); // 5 second duration
            } else {
                // Fallback to original implementation if showBanner is not available
                // Remove any existing error banners
                const existingBanner = document.querySelector('.error-banner');
                if (existingBanner) {
                    existingBanner.remove();
                }

                // Create error banner element
                const banner = document.createElement('div');
                banner.className = 'error-banner fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-[9999999]';
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

        // Real-time search filtering for contacts
        function initializeContactSearch() {
            const contactSearchInput = document.getElementById('contact_search');
            const contactSearchForm = document.getElementById('contactSearchForm');
            const contactTableRows = document.querySelectorAll('#contacts-table tbody tr');
            const tableBody = document.querySelector('#contacts-table tbody');

            if (contactSearchInput && contactSearchForm) {
                // Prevent form submission on Enter key
                contactSearchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterContactTable();
                    }
                });

                // Filter as user types (with debounce for better performance)
                let searchTimeout;
                contactSearchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(filterContactTable, 300); // 300ms delay

                    // Show/hide clear button based on input
                    const clearBtn = document.getElementById('clear_search_btn');
                    if (this.value.trim()) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                });

                // Handle form submission (for search button click)
                contactSearchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    filterContactTable();
                });
            }

            function filterContactTable() {
                const searchTerm = contactSearchInput.value.toLowerCase().trim();

                let visibleCount = 0;
                contactTableRows.forEach(row => {
                    // Skip the "No contacts found" row
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }

                    // Get text content from multiple columns for comprehensive search
                    const contactId = row.cells[0].textContent.toLowerCase();
                    const fullName = row.cells[1].textContent.toLowerCase();
                    const location = row.cells[2].textContent.toLowerCase();
                    const position = row.cells[4].textContent.toLowerCase();

                    // Check if any of the searchable fields contain the search term
                    const searchableText = `${contactId} ${fullName} ${location} ${position}`;
                    if (searchTerm === '' || searchableText.includes(searchTerm)) {
                        row.style.display = ''; // Show row
                        visibleCount++;
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });

                // Handle "No contacts found" display
                handleNoResultsDisplay(visibleCount);

                // Update results count
                updateContactResultsCount(visibleCount);
            }

            // Handle "No contacts found" display
            function handleNoResultsDisplay(visibleCount) {
                // Remove existing "No contacts found" row
                const existingNoResultsRow = tableBody.querySelector('tr td[colspan="6"]');
                if (existingNoResultsRow) {
                    existingNoResultsRow.closest('tr').remove();
                }

                // Add "No contacts found" row if no results
                if (visibleCount === 0) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.innerHTML = `
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-search text-3xl text-gray-300 mb-2"></i>
                                <span>No contacts found matching your search.</span>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(noResultsRow);
                }
            }

            // Make handleNoResultsDisplay available globally for position filter
            window.handleNoResultsDisplay = handleNoResultsDisplay;

            // Update results count based on visible rows
            function updateContactResultsCount(visibleCount) {
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
            if (contactTableRows.length > 0) {
                // Count only actual data rows, not the "No contacts found" row
                const dataRows = Array.from(contactTableRows).filter(row => !row.querySelector('td[colspan]'));
                updateContactResultsCount(dataRows.length);
            }
        }

        // Clear search functionality
        function clearContactSearch() {
            const searchInput = document.getElementById('contact_search');
            const clearBtn = document.getElementById('clear_search_btn');
            const tableBody = document.querySelector('#contacts-table tbody');

            if (searchInput) {
                searchInput.value = '';
                clearBtn.classList.add('hidden');

                // Show all rows
                const contactTableRows = document.querySelectorAll('#contacts-table tbody tr');
                let visibleCount = 0;

                contactTableRows.forEach(row => {
                    // Skip the "No contacts found" row
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }
                    row.style.display = '';
                    visibleCount++;
                });

                // Remove any existing "No contacts found" message
                const existingNoResultsRow = tableBody.querySelector('tr td[colspan="6"]');
                if (existingNoResultsRow) {
                    existingNoResultsRow.closest('tr').remove();
                }

                // Update results count
                updateContactResultsCount(visibleCount);
            }
        }

        // Handle position filter
        function handlePositionFilter() {
            const positionSelect = document.getElementById('contact_position_filter');
            const tableBody = document.querySelector('#contacts-table tbody');
            const contactTableRows = document.querySelectorAll('#contacts-table tbody tr');

            if (positionSelect) {
                const selectedPosition = positionSelect.value;

                let visibleCount = 0;
                contactTableRows.forEach(row => {
                    // Skip the "No contacts found" row
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }

                    // Get position from the row (5th column, 0-indexed)
                    const positionCell = row.cells[4];
                    const rowPosition = positionCell ? positionCell.textContent.trim() : '';

                    // Show/hide row based on position filter
                    if (selectedPosition === '' || rowPosition === selectedPosition) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Handle "No contacts found" display
                handleNoResultsDisplay(visibleCount);

                // Update results count
                updateContactResultsCount(visibleCount);
            }
        }

        // Contact number management functions
        function addContactNumber() {
            const container = document.getElementById('contact-numbers-container');
            const fieldCount = container.querySelectorAll('.contact-number-field').length;
            const newField = document.createElement('div');
            newField.className = 'contact-number-field flex items-center gap-2 mb-2';
            newField.innerHTML = `
                <input type="text" name="contact_numbers[]" required class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Enter contact number">
                <button type="button" onclick="removeContactNumber(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newField);

            // Show remove buttons if there are multiple fields
            updateRemoveButtons();
        }

        function removeContactNumber(button) {
            const field = button.closest('.contact-number-field');
            field.remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const container = document.getElementById('contact-numbers-container');
            const fields = container.querySelectorAll('.contact-number-field');
            const removeButtons = container.querySelectorAll('button[onclick="removeContactNumber(this)"]');

            removeButtons.forEach(button => {
                button.style.display = fields.length > 1 ? 'block' : 'none';
            });
        }

        function addEditContactNumber(value = '', index = null) {
            const container = document.getElementById('edit-contact-numbers-container');
            const fieldCount = container.querySelectorAll('.contact-number-field').length;
            const actualIndex = index !== null ? index : fieldCount;
            const newField = document.createElement('div');
            newField.className = 'contact-number-field flex items-center gap-2 mb-2';
            newField.innerHTML = `
                <input type="text" name="contact_numbers[]" value="${value}" required class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Enter contact number">
                <button type="button" onclick="removeEditContactNumber(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newField);

            // Show remove buttons if there are multiple fields
            updateEditRemoveButtons();
        }

        function removeEditContactNumber(button) {
            const field = button.closest('.contact-number-field');
            field.remove();
            updateEditRemoveButtons();
        }

        function updateEditRemoveButtons() {
            const container = document.getElementById('edit-contact-numbers-container');
            const fields = container.querySelectorAll('.contact-number-field');
            const removeButtons = container.querySelectorAll('button[onclick="removeEditContactNumber(this)"]');

            removeButtons.forEach(button => {
                button.style.display = fields.length > 1 ? 'block' : 'none';
            });
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, firstName, middleName, lastName, location, contact_numbers, position) {
            // Populate form fields with current data
            document.getElementById('edit_first_name').value = firstName;
            document.getElementById('edit_middle_name').value = middleName || '';
            document.getElementById('edit_last_name').value = lastName;
            
            // Handle location dropdown vs manual input
            const locationSelect = document.getElementById('edit_brgy_location_select');
            const locationManual = document.getElementById('edit_brgy_location_manual');
            
            // Check if location exists in dropdown options
            const locationExists = Array.from(locationSelect.options).some(option => option.value === location);
            
            if (locationExists && location !== 'Other') {
                // Use dropdown
                locationSelect.value = location;
                // Show dropdown, hide manual input (Alpine.js will handle this)
            } else {
                // Use manual input
                locationSelect.value = 'Other';
                // Trigger Alpine.js to show manual input
                locationSelect.dispatchEvent(new Event('change'));
                setTimeout(() => {
                    if (locationManual) {
                        locationManual.value = location;
                    }
                }, 100);
            }

            // Handle multiple contact numbers
            const contactNumbersArray = contact_numbers ? JSON.parse(contact_numbers) : [];
            const container = document.getElementById('edit-contact-numbers-container');
            container.innerHTML = '';

            contactNumbersArray.forEach((number, index) => {
                addEditContactNumber(number, index);
            });

            if (contactNumbersArray.length === 0) {
                addEditContactNumber('', 0);
            }
            
            document.getElementById('edit_position').value = position || '';
            
            // Set the form action URL
            document.getElementById('editForm').action = '{{ route("contacts.update", "") }}/' + id;
            
            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
            
            // Focus on the first input field
            document.getElementById('edit_first_name').focus();
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            // Clear form data when closing
            document.getElementById('editForm').reset();
        }

        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteConfirmationModal');
            if (modal && e.target === modal) {
                closeDeleteConfirmationModal();
            }
        });

        // Load notifications function
        async function loadNotifications() {
            try {
                const response = await fetch('/notifications', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update notification dropdown if it exists
                    const notificationDropdown = document.getElementById('notificationDropdown');
                    if (notificationDropdown) {
                        // This would update the notification UI - simplified for contacts page
                        console.log('Notifications loaded:', data);
                    }
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Initialize notification polling
        function initializeNotificationPolling() {
            // Load notifications immediately
            loadNotifications();

            // Set up polling every 30 seconds
            setInterval(loadNotifications, 30000);
        }

        // Function to create contact notifications
        function createContactNotification(action, contactName, location) {
            // Prepare notification data based on action
            let notificationData = {
                type: 'new_contact',
                title: '',
                message: '',
                data: {
                    contact_name: contactName,
                    location: location,
                    action: action
                }
            };

            // Set title and message based on action
            switch(action) {
                case 'add':
                    notificationData.title = 'New Contact Added';
                    notificationData.message = `New contact ${contactName} added for ${location}`;
                    break;
                case 'update':
                    notificationData.title = 'Contact Updated';
                    notificationData.message = `Contact ${contactName} from ${location} has been updated`;
                    break;
                case 'delete':
                    notificationData.title = 'Contact Deleted';
                    notificationData.message = `Contact ${contactName} from ${location} has been deleted`;
                    break;
                default:
                    notificationData.title = 'Contact Action';
                    notificationData.message = `Contact ${contactName} action performed`;
            }

            // Send AJAX request to create notification
            fetch('/notifications/create-contact-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(notificationData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Contact notification created successfully:', data);
                    // Refresh notifications in the bell icon
                    if (typeof loadNotifications === 'function') {
                        loadNotifications();
                    }
                    // Also trigger a manual refresh of the notification badge
                    if (typeof window.updateNotificationBadge === 'function') {
                        window.updateNotificationBadge();
                    }
                } else {
                    console.error('Failed to create contact notification:', data.message);
                }
            })
            .catch(error => {
                console.error('Error creating contact notification:', error);
            });
        }

        // Delete contact function with confirmation modal and success banner
        function deleteContact(contactId, contactName) {
            // Store the contact info for deletion
            window.contactToDelete = { id: contactId, name: contactName };

            // Show confirmation modal
            showDeleteConfirmationModal(contactName);
        }

        function showDeleteConfirmationModal(contactName) {
            // Remove any existing delete modal
            const existingModal = document.getElementById('deleteConfirmationModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Create modal HTML
            const modalHTML = `
                <div id="deleteConfirmationModal" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm">
                    <div class="bg-white rounded-lg shadow-2xl w-full max-w-sm relative transform transition-all duration-300 scale-100">
                        <!-- Modal Header -->
                        <div class="bg-white border-b border-red-200 px-4 py-3 rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-red-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-gray-900">Confirm Deletion</h2>
                                        <p class="text-red-600 text-xs">This action cannot be undone</p>
                                    </div>
                                </div>
                                <button onclick="closeDeleteConfirmationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-times text-base"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-4">
                            <div class="text-center">
                                <div class="w-11 h-11 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-trash text-red-600 text-base"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Delete Contact</h3>
                                <p class="text-gray-600 mb-4 text-xs">
                                    Are you sure you want to delete the contact <strong>"${contactName}"</strong>?
                                    This action cannot be undone.
                                </p>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end items-center gap-2 p-4 pt-0">
                            <button onclick="closeDeleteConfirmationModal()" class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors flex items-center gap-1 text-xs">
                                <i class="fas fa-times text-xs"></i>
                                Cancel
                            </button>
                            <button onclick="confirmDeleteContact()" class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition-colors flex items-center gap-1 text-xs">
                                <i class="fas fa-trash text-xs"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to page
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Show modal with animation
            setTimeout(() => {
                const modal = document.getElementById('deleteConfirmationModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            }, 10);
        }

        function closeDeleteConfirmationModal() {
            const modal = document.getElementById('deleteConfirmationModal');
            if (modal) {
                modal.classList.add('hidden');
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }
        }

        function confirmDeleteContact() {
            const contact = window.contactToDelete;
            if (!contact) return;

            closeDeleteConfirmationModal();

            // Find the delete button and add loading state
            const deleteButtons = document.querySelectorAll('button[onclick*="deleteContact"]');
            let deleteButton = null;
            let originalContent = '';

            deleteButtons.forEach(button => {
                if (button.onclick.toString().includes(contact.id)) {
                    deleteButton = button;
                    originalContent = button.innerHTML;
                }
            });

            if (deleteButton) {
                deleteButton.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
                deleteButton.disabled = true;
            }

            // Prepare form data for deletion
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'DELETE');

            // Send delete request
            fetch(`/contacts/${contact.id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success banner
                    showSuccessAlert(data.message || 'Contact deleted successfully.');

                    // Create notification for deleted contact
                    createContactNotification('delete', contact.name, 'Unknown Location');

                    // Remove the contact row from the table
                    const contactRows = document.querySelectorAll('#contacts-table tbody tr');
                    contactRows.forEach(row => {
                        const contactIdCell = row.cells[0];
                        if (contactIdCell && contactIdCell.textContent.trim() === contact.id) {
                            row.remove();
                        }
                    });

                    // Update statistics cards
                    updateContactStatistics();
                } else {
                    // Show error message
                    alert('Error: ' + (data.message || 'Failed to delete contact'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the contact.');
            })
            .finally(() => {
                // Restore button state
                if (deleteButton && originalContent) {
                    deleteButton.innerHTML = originalContent;
                    deleteButton.disabled = false;
                }
            });
        }

        // Function to update contact statistics after deletion
        function updateContactStatistics() {
            const totalContactsElement = document.querySelector('.bg-white.rounded-lg.shadow-sm.border.border-gray-200.p-6:nth-child(1) .text-2xl.font-bold');
            const activeContactsElement = document.querySelector('.bg-white.rounded-lg.shadow-sm.border.border-gray-200.p-6:nth-child(2) .text-2xl.font-bold');

            if (totalContactsElement && activeContactsElement) {
                const currentTotal = parseInt(totalContactsElement.textContent);
                const currentActive = parseInt(activeContactsElement.textContent);

                if (currentTotal > 0) {
                    totalContactsElement.textContent = currentTotal - 1;
                    activeContactsElement.textContent = currentActive - 1;
                }
            }
        }

        // Helper function to check if location exists (placeholder)
        async function checkLocationExists(location) {
            // This would typically make an AJAX request to check if the location exists
            // For now, we'll just return false to indicate it doesn't exist
            return false;
        }

        // Handle location submission for both add and edit forms
        function handleLocationSubmission() {
            // Before form submission, ensure the correct field has the name attribute
            const addDropdown = document.getElementById('add_brgy_location_select');
            const addManual = document.getElementById('add_brgy_location_manual');
            const editDropdown = document.getElementById('edit_brgy_location_select');
            const editManual = document.getElementById('edit_brgy_location_manual');
            
            // For Add Modal
            if (addDropdown && addManual) {
                if (addDropdown.style.display !== 'none' && addDropdown.value !== 'Other' && addDropdown.value !== '') {
                    // Dropdown is active and has a valid value
                    addDropdown.setAttribute('name', 'brgy_location');
                    addManual.removeAttribute('name');
                } else if (addManual.style.display !== 'none' && addManual.value !== '') {
                    // Manual input is active and has a value
                    addManual.setAttribute('name', 'brgy_location');
                    addDropdown.removeAttribute('name');
                }
            }
            
            // For Edit Modal
            if (editDropdown && editManual) {
                if (editDropdown.style.display !== 'none' && editDropdown.value !== 'Other' && editDropdown.value !== '') {
                    // Dropdown is active and has a valid value
                    editDropdown.setAttribute('name', 'brgy_location');
                    editManual.removeAttribute('name');
                } else if (editManual.style.display !== 'none' && editManual.value !== '') {
                    // Manual input is active and has a value
                    editManual.setAttribute('name', 'brgy_location');
                    editDropdown.removeAttribute('name');
                }
            }
        }

        @if($errors->any())
        // Auto-open the Add modal when there are validation errors from store
        document.addEventListener('DOMContentLoaded', function() {
            // Add a small delay to ensure the modal is fully loaded
            setTimeout(() => {
                openAddModal();
            }, 100);
        });
        @endif
    </script>
</div>
@endsection
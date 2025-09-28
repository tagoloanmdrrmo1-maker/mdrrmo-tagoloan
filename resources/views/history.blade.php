@extends('layouts.app')

@section('title', 'Rainfall History - MDDRMO Rainfall Monitoring')
@section('page_heading', 'Rainfall History')

@section('content')


<div class="container mx-auto px-4 py-6">
    <!-- Tab Navigation -->
    <div class="flex mb-6 w-full">
        <div class="bg-gray-100 rounded-full flex p-1 w-full">
            <button 
                class="tab-button flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-full text-base font-medium transition-all duration-200 bg-white text-blue-600 shadow"
                data-tab="historical"
            >
                <i class="fas fa-bolt"></i>
                Historical Data
            </button>
            <button 
                class="tab-button flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-full text-base font-medium transition-all duration-200 text-gray-600"
                data-tab="trend"
            >
                <i class="fas fa-chart-line"></i>
                Trend Analysis
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs font-medium mb-1">Total Rainfall</p>
                    <p class="text-xl font-semibold text-blue-600">{{ $statistics['totalRainfall'] ?? '0' }} mm</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 19l2-2 2 2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17v2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l2-2 2 2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 17v2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 19l2-2 2 2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 17v2"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs font-medium mb-1">Average Rainfall</p>
                    <p class="text-xl font-semibold text-green-600">{{ $statistics['avgRainfall'] ?? '0' }} mm</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs font-medium mb-1">Active Stations</p>
                    <p class="text-xl font-semibold text-purple-600">{{ $statistics['activeStations'] ?? '0' }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Historical Data Content (Visible by default) -->
    <div id="historical-content">
        <!-- Rainfall Data Analysis Section -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6" x-data="{ 
            selectedType: '{{ request('type', '') }}',
            submitted: {{ request('show', '1') == '1' ? 'true' : 'false' }}
        }" style="display: block;">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Rainfall Data Analysis</h2>
            </div>

            {{-- Filter Form --}}
            <form 
                method="GET" 
                action="{{ route('history') }}" 
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-8 items-end gap-3 mb-6"
            >
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Select Type</label>
                    <select 
                        id="type" 
                        name="type" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        x-model="selectedType"
                    >
                        <option value="">Select Type</option>
                        <option value="Rainfall" {{ request('type') == 'Rainfall' ? 'selected' : '' }}>Historical Rainfall</option>
                        <option value="Average Rainfall per day" {{ request('type') == 'Average Rainfall per day' ? 'selected' : '' }}>Daily Average Rainfall</option>
                        <option value="Average Rainfall per month" {{ request('type') == 'Average Rainfall per month' ? 'selected' : '' }}>Monthly Average Rainfall</option>
                    </select>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <select id="location" name="location" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                    <select id="month" name="month" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                @php
                                    $monthFormat = DateTime::createFromFormat('!m', $m);
                                @endphp
                                {{ $monthFormat ? $monthFormat->format('F') : 'Month ' . $m }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div x-show="selectedType !== 'Average Rainfall per month'">
                    <label for="day" class="block text-sm font-medium text-gray-700">Day</label>
                    <select id="day" name="day" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All</option>
                        @foreach(range(1,31) as $d)
                            <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <select id="year" name="year" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All</option>
                        @foreach(range(date('Y')-5, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="selectedType === 'Rainfall'">
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <select id="start_time" name="start_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All</option>
                        @foreach(range(0,23) as $h)
                            <option value="{{ sprintf('%02d:00', $h) }}" {{ request('start_time') == sprintf('%02d:00', $h) ? 'selected' : '' }}>{{ sprintf('%02d:00', $h) }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="selectedType === 'Rainfall'">
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                    <select id="end_time" name="end_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">All</option>
                        @foreach(range(0,23) as $h)
                            <option value="{{ sprintf('%02d:00', $h) }}" {{ request('end_time') == sprintf('%02d:00', $h) ? 'selected' : '' }}>{{ sprintf('%02d:00', $h) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-1 md:col-span-2 lg:col-span-1 justify-end">
                    <input type="hidden" name="show" value="1" />
                    <button type="button" id="resetFormBtn" class="w-10 h-10 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-700 shadow-sm transition-colors flex items-center justify-center" onclick="resetFilterForm()" title="Reset Form">
                        <svg class="w-10 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                    <button id="showTableBtn"
                        type="submit"
                        :disabled="!['Rainfall','Average Rainfall per day','Average Rainfall per month'].includes(selectedType)"
                        class="h-10 px-4 text-white font-medium rounded-lg transition-colors text-sm whitespace-nowrap flex items-center"
                        :class="['Rainfall','Average Rainfall per day','Average Rainfall per month'].includes(selectedType) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                        title="Select a valid Type to enable"
                    >
                        Show Table
                    </button>
                </div>
            </form>

            @if(request('show') == '1' && request('type') && trim(request('type')) !== '' && !in_array(request('type'), ['Rainfall', 'Average Rainfall per day', 'Average Rainfall per month']))
            <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-red-700 text-sm">
                Please select a valid Type before showing the table.
            </div>
            @endif

            @if(request('show') == '1' && request('type') && trim(request('type')) !== '' && request('type') === 'Rainfall')
            <div class="overflow-x-auto" id="rainfall-container">
                <!-- Download and Print Buttons -->
                <div class="flex justify-end gap-1 mb-1">
                    <button id="downloadHistoryBtn" class="px-2 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="exportRainfallPdf('rainfall')">
                        <i class="fas fa-download text-xs"></i> Download PDF
                    </button>
                    <button id="printHistoryBtn" type="button" class="px-2 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="printTable('rainfall-table')">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>

                <!-- Rainfall Table -->
                <div id="RainfallTable">
                    <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
                        <!-- Header -->
                        <div class="bg-[#242F41] px-4 py-3 rounded-t-xl">
                            <h3 class="text-lg font-light text-white flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                                Historical Rainfall Data
                            </h3>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table id="rainfall-table" class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Device ID</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Location</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Month</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Day</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Year</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider time-column">Recorded Time</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Tips</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Rainfall (mm)</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Intensity</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @if(count($rainfallData) > 0)
                                        @foreach($rainfallData as $index => $data)
                                            <tr class="hover:bg-blue-50 transition-colors duration-150 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                <!-- Device ID -->
                                                <td class="px-4 py-2 text-sm text-gray-900">
                                                    {{ $data->dev_id }}
                                                </td>
                                                <!-- Location -->
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ $data->dev_location }}
                                                </td>
                                                <!-- Month -->
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                    @php
                                                        $monthFormat = is_numeric($data->month) ? \Carbon\Carbon::createFromFormat('!m', (int)$data->month) : null;
                                                    @endphp
                                                    {{ $monthFormat ? $monthFormat->format('M') : $data->month }}
                                                </td>
                                                <!-- Day -->
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                    {{ $data->day }}
                                                </td>
                                                <!-- Year -->
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                    {{ $data->year }}
                                                </td>
                                                <!-- Time -->
                                                <td class="px-4 py-2 text-center text-sm text-gray-700 time-column">
                                                    @if($data->created_at)
                                                        {{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </td>
                                                <!-- Tips -->
                                                <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                    {{ $data->tip_count }}
                                                </td>
                                                <!-- Rainfall -->
                                                <td class="px-4 py-2 text-center text-sm font-semibold text-blue-600">
                                                    {{ $data->cumulative_rainfall }}
                                                </td>
                                                <!-- Intensity -->
                                                <td class="px-4 py-2 text-center">
                                                    @if($data->intensity_level)
                                                        @php
                                                            $colorClass = match($data->intensity_level) {
                                                                'Torrential' => 'bg-red-100 text-red-800 border-red-200',
                                                                'Intense' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                                'Heavy' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                                'Moderate' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                                'Light' => 'bg-green-100 text-green-800 border-green-200',
                                                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                                                            };
                                                        @endphp
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold border {{ $colorClass }}">
                                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                               
                                                            </svg>
                                                            {{ $data->intensity_level }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                                No rainfall data available.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination for Rainfall Data -->
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <!-- Mobile Pagination (Previous & Next only) -->
                            <div class="flex-1 flex justify-between sm:hidden">
                                @if($rainfallData->onFirstPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $rainfallData->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Previous
                                    </a>
                                @endif

                                @if($rainfallData->hasMorePages())
                                    <a href="{{ $rainfallData->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                        <span class="font-medium">{{ $rainfallData->firstItem() ?? 0 }}</span>
                                        to
                                        <span class="font-medium">{{ $rainfallData->lastItem() ?? 0 }}</span>
                                        of
                                        <span class="font-medium">{{ $rainfallData->total() }}</span>
                                        results
                                    </p>
                                </div>

                                <!-- Numbers + Previous/Next -->
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <!-- Previous Page Link -->
                                        @if($rainfallData->onFirstPage())
                                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed"><</span>
                                        @else
                                            <a href="{{ $rainfallData->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md"><</a>
                                        @endif

                                        <!-- Pagination Elements -->
                                        @foreach ($rainfallData->getUrlRange(1, $rainfallData->lastPage()) as $page => $url)
                                            @if ($page == $rainfallData->currentPage())
                                                <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                                            @endif
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if($rainfallData->hasMorePages())
                                            <a href="{{ $rainfallData->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">></a>
                                        @else
                                            <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">></span>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(request('show') == '1' && request('type') && trim(request('type')) !== '' && request('type') === 'Average Rainfall per day')
            <div class="overflow-x-auto mt-6" id="avg-day-container">
                <!-- Download and Print Buttons -->
                <div class="flex justify-end gap-1 mb-3">
                    <button type="button" class="px-2 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="exportRainfallPdf('average_daily')">
                        <i class="fas fa-download text-xs"></i> Download PDF
                    </button>
                    <button type="button" class="px-2 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="printTable('avg-day-table')">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
                <!-- Average Rainfall per Day Table -->
                <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
                    <div class="bg-[#242F41] px-4 py-3 rounded-t-xl">
                        <h3 class="text-lg font-light text-white flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4a1 1 0 011-1h3l1 2h8l1-2h3a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/></svg>
                            Average Rainfall per Day
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="avg-day-table" class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Device ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Location</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Month</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Day</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Year</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Total Rainfall (mm)</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Average Rainfall (mm)</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @if(count($averagePerDay) > 0)
                                    @foreach($averagePerDay as $index => $data)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $data->dev_id }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $data->dev_location }}</td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                @php
                                                    $monthFormat = is_numeric($data->month) ? \Carbon\Carbon::createFromFormat('!m', (int)$data->month) : null;
                                                @endphp
                                                {{ $monthFormat ? $monthFormat->format('M') : ($data->month ?? 'N/A') }}
                                            </td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $data->day ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $data->year ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $data->total_rainfall ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-center text-sm font-semibold text-blue-600">{{ $data->average_rainfall }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'Average Rainfall per day', 'detail_day' => $data->date, 'dev_location' => $data->dev_location]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 transition-colors duration-200" title="View hourly breakdown">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No average rainfall per day data available.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Daily Average Data -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <!-- Mobile Pagination -->
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($averagePerDay->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $averagePerDay->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                            @endif
                            @if($averagePerDay->hasMorePages())
                                <a href="{{ $averagePerDay->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                        <!-- Desktop Pagination -->
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $averagePerDay->firstItem() ?? 0 }}</span>
                                    to <span class="font-medium">{{ $averagePerDay->lastItem() ?? 0 }}</span>
                                    of <span class="font-medium">{{ $averagePerDay->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    @if($averagePerDay->onFirstPage())
                                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed">Previous</span>
                                    @else
                                        <a href="{{ $averagePerDay->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md">Previous</a>
                                    @endif
                                    @foreach ($averagePerDay->getUrlRange(1, $averagePerDay->lastPage()) as $page => $url)
                                        @if ($page == $averagePerDay->currentPage())
                                            <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                    @if($averagePerDay->hasMorePages())
                                        <a href="{{ $averagePerDay->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">Next</a>
                                    @else
                                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">Next</span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(request('show') == '1' && request('type') && trim(request('type')) !== '' && request('type') === 'Average Rainfall per month')
            <div class="overflow-x-auto mt-6" id="avg-month-container">
                <!-- Download and Print Buttons -->
                <div class="flex justify-end gap-1 mb-3">
                    <button id="downloadHistoryBtn" class="px-2 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="exportRainfallPdf('average_monthly')">
                        <i class="fas fa-download text-xs"></i> Download PDF
                    </button>
                    <button id="printHistoryBtn" class="px-2 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs font-medium transition-colors flex items-center gap-1" onclick="printTable('avg-month-table')">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
                <!-- Average Rainfall per Month Table -->
                <div class="bg-gray-200 rounded-xl shadow-lg border border-gray-300 overflow-hidden max-w-full">
                    <div class="bg-[#242F41] px-4 py-3 rounded-t-xl">
                        <h3 class="text-lg font-light text-white flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2a1 1 0 00-1 1v1H3a1 1 0 000 2h2v2H3a1 1 0 000 2h2v2H3a1 1 0 000 2h2v1a1 1 0 001 1h12a1 1 0 001-1V3a1 1 0 00-1-1H6z"/></svg>
                            Average Rainfall per Month
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="avg-month-table" class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Device ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 tracking-wider">Location</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Month</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Year</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Total Rainfall (mm)</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Average Rainfall (mm)</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @if(count($averagePerMonth) > 0)
                                    @foreach($averagePerMonth as $index => $data)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $data->dev_id }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $data->dev_location }}</td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                @php
                                                    $monthFormat = is_numeric($data->month) ? \DateTime::createFromFormat('!m', (int)$data->month) : null;
                                                @endphp
                                                {{ $monthFormat ? $monthFormat->format('F') : 'Month ' . $data->month }}
                                            </td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $data->year }}</td>
                                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $data->total_rainfall ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-center text-sm font-semibold text-blue-600">{{ $data->average_rainfall }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'Average Rainfall per month', 'detail_month' => (int)$data->month, 'detail_year' => $data->year, 'dev_location' => $data->dev_location]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 transition-colors duration-200" title="View daily breakdown">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No average rainfall per month data available.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Monthly Average Data -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <!-- Mobile Pagination -->
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($averagePerMonth->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $averagePerMonth->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                            @endif
                            @if($averagePerMonth->hasMorePages())
                                <a href="{{ $averagePerMonth->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                        <!-- Desktop Pagination -->
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $averagePerMonth->firstItem() ?? 0 }}</span>
                                    to <span class="font-medium">{{ $averagePerMonth->lastItem() ?? 0 }}</span>
                                    of <span class="font-medium">{{ $averagePerMonth->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    @if($averagePerMonth->onFirstPage())
                                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-l-md cursor-not-allowed">Previous</span>
                                    @else
                                        <a href="{{ $averagePerMonth->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md">Previous</a>
                                    @endif
                                    @foreach ($averagePerMonth->getUrlRange(1, $averagePerMonth->lastPage()) as $page => $url)
                                        @if ($page == $averagePerMonth->currentPage())
                                            <span class="px-3 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                    @if($averagePerMonth->hasMorePages())
                                        <a href="{{ $averagePerMonth->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-r-md">Next</a>
                                    @else
                                        <span class="px-3 py-2 border border-gray-300 text-sm font-medium text-gray-400 bg-gray-100 rounded-r-md cursor-not-allowed">Next</span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Modal: Daily breakdown for a specific day -->
        @if(!empty($detailDayData))
        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[9999999] flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="px-6 py-4 border-b bg-[#242F41] text-white rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                            </div>
                        <div>
                                <h3 class="text-lg font-semibold">Hourly Rainfall Breakdown</h3>
                                <p class="text-sm opacity-90">{{ $detailDayData['dev_location'] }} •
                            @php
                                $dateFormat = \Carbon\Carbon::createFromFormat('Y-m-d', $detailDayData['date']);
                            @endphp
                                {{ $dateFormat ? $dateFormat->format('F j, Y') : $detailDayData['date'] }}
                        </p>
                        </div>
                        </div>
                        <button onclick="window.history.back()" class="text-white hover:text-gray-200 transition-colors p-2 hover:bg-white hover:bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        </div>
                    </div>

                <!-- Content -->
                <div class="flex-1 overflow-hidden flex flex-col">
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-6">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Tips</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ number_format($detailDayData['total_tips']) }}</p>
            </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                                        <p class="text-sm font-semibold text-gray-700">
                                            @php
                                                $dateFormat = \Carbon\Carbon::createFromFormat('Y-m-d', $detailDayData['date']);
                                            @endphp
                                            {{ $dateFormat ? $dateFormat->format('M j, Y') : $detailDayData['date'] }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $detailDayData['dev_location'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time Period</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tips</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Rainfall (mm)</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Intensity</th>
                        </tr>
                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailDayData['hours'] as $h => $val)
                            @if($val['rainfall'] > 0)
                                        <tr class="hover:bg-blue-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $val['start_time'] }} - {{ $val['end_time'] }}
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-sm text-gray-600">
                                                {{ $val['tips'] }}
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-sm font-semibold text-blue-600">
                                                {{ number_format($val['rainfall'], 1) }} mm
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @php
                                        $intensity = $val['intensity_level'] ?? 'No Rain';
                                        $colorClass = match($intensity) {
                                                        'Torrential' => 'bg-red-100 text-red-800 border-red-200',
                                                        'Intense' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                        'Heavy' => 'bg-green-100 text-green-800 border-green-200',
                                                        'Moderate' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'Light' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                        'No Rain' => 'bg-gray-50 text-gray-500 border-gray-100',
                                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        };
                                    @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colorClass }}">
                                        {{ $intensity }}
                                    </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif


    <!-- Modal: Daily breakdown for a specific month -->
    @if(!empty($detailMonthData))
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[9999999] flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-[#242F41] text-white rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 2a1 1 0 00-1 1v1H3a1 1 0 000 2h2v2H3a1 1 0 000 2h2v2H3a1 1 0 000 2h2v1a1 1 0 001 1h12a1 1 0 001-1V3a1 1 0 00-1-1H6z"/>
                            </svg>
                        </div>
                    <div>
                            <h3 class="text-lg font-semibold">Daily Rainfall Breakdown</h3>
                            <p class="text-sm opacity-90">{{ $detailMonthData['dev_location'] }} •
                            @php
                                $monthFormat = \Carbon\Carbon::createFromFormat('!m', $detailMonthData['month']);
                            @endphp
                            {{ $monthFormat ? $monthFormat->format('F') : 'Month ' . $detailMonthData['month'] }}, {{ $detailMonthData['year'] }}
                        </p>
                    </div>
                    </div>
                    <button onclick="window.history.back()" class="text-white hover:text-gray-200 transition-colors p-2 hover:bg-white hover:bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    </div>
                </div>

            <!-- Content -->
            <div class="flex-1 overflow-hidden flex flex-col">
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Tips</p>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($detailMonthData['total_tips']) }}</p>
            </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Month</p>
                                    <p class="text-sm font-semibold text-gray-700">
                                        @php
                                            $monthFormat = \Carbon\Carbon::createFromFormat('!m', $detailMonthData['month']);
                                        @endphp
                                        {{ $monthFormat ? $monthFormat->format('F Y') : 'Month ' . $detailMonthData['month'] . ' ' . $detailMonthData['year'] }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ $detailMonthData['dev_location'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Day</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tips</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Rainfall (mm)</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Intensity</th>
                        </tr>
                    </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailMonthData['days'] as $d => $val)
                            @if($val['rainfall'] > 0)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $d }}
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-sm text-gray-600">
                                                {{ $val['tips'] }}
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-sm font-semibold text-green-600">
                                                {{ number_format($val['rainfall'], 1) }} mm
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @php
                                        $intensity = $val['intensity_level'] ?? 'No Rain';
                                        $colorClass = match($intensity) {
                                                        'Torrential' => 'bg-red-100 text-red-800 border-red-200',
                                                        'Intense' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                        'Heavy' => 'bg-green-100 text-green-800 border-green-200',
                                                        'Moderate' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'Light' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                        'No Rain' => 'bg-gray-50 text-gray-500 border-gray-100',
                                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        };
                                    @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colorClass }}">
                                        {{ $intensity }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                    <div class="text-sm text-gray-600 text-center">
                        @php
                            $count = count(array_filter($detailMonthData['days'], function($val) { return $val['rainfall'] > 0; }));
                        @endphp
                        <span class="font-medium">Showing {{ $count }} daily records</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

    <!-- Trend Analysis Content (Hidden by default) -->
    <div id="trend-content" class="hidden">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Monthly Rainfall Trend Analysis – {{ request('year', date('Y')) }}
                </h2>
                <div class="flex gap-2">
                    <button id="resetChartBtn" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium transition-colors">
                        <i class="fas fa-undo mr-1"></i>Reset
                    </button>
                </div>
            </div>
            
            <!-- Trend Analysis Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="trendLocation" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <select id="trendLocation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Locations</option>
                        @isset($locations)
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                
                <div>
                    <label for="trendYear" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select id="trendYear" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Year</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label for="trendPeriod" class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <select id="trendPeriod" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Periods</option>
                        <option value="q1">Q1 (Jan-Mar)</option>
                        <option value="q2">Q2 (Apr-Jun)</option>
                        <option value="q3">Q3 (Jul-Sep)</option>
                        <option value="q4">Q4 (Oct-Dec)</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button id="analyzeTrendsBtn" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>Analyze Trends
                    </button>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="h-96">
                <canvas id="rainfallChart"></canvas>
            </div>
            
            <!-- Chart Legend -->
            <div class="flex justify-center mt-4 space-x-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Current Year</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-0.5 bg-green-500 mr-2 mt-1" style="width: 16px;"></div>
                    <span class="text-sm text-gray-600">Historical Average</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const historicalContent = document.getElementById('historical-content');
        const trendContent = document.getElementById('trend-content');

        // Set initial state - show historical content by default
        historicalContent.classList.remove('hidden');
        trendContent.classList.add('hidden');
        
        // Set initial active state for the historical tab
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-tab') === 'historical') {
                btn.classList.add('bg-white', 'text-blue-600', 'shadow');
                btn.classList.remove('text-gray-600');
            }
        });
        
        // Add click event to each tab button
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active classes from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-white', 'text-blue-600', 'shadow');
                    btn.classList.add('text-gray-600');
                });
                
                // Add active classes to clicked button
                this.classList.add('bg-white', 'text-blue-600', 'shadow');
                this.classList.remove('text-gray-600');
                
                // Show/hide content based on selected tab
                const tab = this.getAttribute('data-tab');
                if (tab === 'historical') {
                    historicalContent.classList.remove('hidden');
                    trendContent.classList.add('hidden');
                } else {
                    historicalContent.classList.add('hidden');
                    trendContent.classList.remove('hidden');
                }
            });
            
            // Improved hover effect that doesn't interfere with active state
            button.addEventListener('mouseenter', function() {
                // Only apply hover effect if not the active tab
                if (!this.classList.contains('bg-white')) {
                    this.classList.add('hover:bg-white', 'hover:text-blue-600');
                    this.classList.remove('text-gray-600');
                }
            });
            
            button.addEventListener('mouseleave', function() {
                // Remove hover effect but preserve active state
                if (!this.classList.contains('bg-white')) {
                    this.classList.remove('hover:bg-white', 'hover:text-blue-600');
                    this.classList.add('text-gray-600');
                }
            });
        });
        
        // Chart initialization
        const ctx = document.getElementById('rainfallChart').getContext('2d');
        
        // Sample data - in a real application, this would come from the server
        const currentYearData = [78, 62, 90, 110, 150, 200, 180, 170, 140, 100, 85, 70];
        const historicalAverageData = [65, 60, 70, 95, 120, 180, 160, 155, 130, 95, 75, 65];
        
        const rainfallChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: 'Current Year',
                        data: currentYearData,
                        borderColor: 'rgba(59, 130, 246, 1)', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Historical Average',
                        data: historicalAverageData,
                        borderColor: 'rgba(34, 197, 94, 1)', // green-500
                        borderDash: [6, 4],
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 10,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Rainfall (mm)',
                            font: {
                                size: 14,
                                weight: 'normal'
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        // Trend analysis functionality
        document.getElementById('analyzeTrendsBtn').addEventListener('click', function() {
            const location = document.getElementById('trendLocation').value;
            const year = document.getElementById('trendYear').value;
            const period = document.getElementById('trendPeriod').value;
            
            // In a real application, this would make an AJAX request to fetch new data
            console.log('Analyzing trends with filters:', { location, year, period });
            
            // For demonstration, we'll just show an alert
            alert('Analyzing trends for ' + (location || 'All Locations') + ' in ' + (year || 'selected year'));
        });
        
        // Reset chart functionality
        document.getElementById('resetChartBtn').addEventListener('click', function() {
            // Reset form fields
            document.getElementById('trendLocation').value = '';
            document.getElementById('trendYear').value = '';
            document.getElementById('trendPeriod').value = '';
            
            // In a real application, this would reset the chart to default data
            console.log('Chart reset to default');
            alert('Chart reset to default view');
        });
        
        // Function to export rainfall data as PDF
        window.exportRainfallPdf = function(type) {
            // Get current filter parameters
            const searchParams = new URLSearchParams();
            
            // Get form filter parameters
            const typeSelect = document.querySelector('select[name="type"]');
            if (typeSelect && typeSelect.value) {
                searchParams.append('type', type);
            }
            
            const locationSelect = document.querySelector('select[name="location"]');
            if (locationSelect && locationSelect.value) {
                searchParams.append('location', locationSelect.value);
            }
            
            const monthSelect = document.querySelector('select[name="month"]');
            if (monthSelect && monthSelect.value) {
                searchParams.append('month', monthSelect.value);
            }
            
            const daySelect = document.querySelector('select[name="day"]');
            if (daySelect && daySelect.value) {
                searchParams.append('day', daySelect.value);
            }
            
            const yearSelect = document.querySelector('select[name="year"]');
            if (yearSelect && yearSelect.value) {
                searchParams.append('year', yearSelect.value);
            }
            
            const startTimeSelect = document.querySelector('select[name="start_time"]');
            if (startTimeSelect && startTimeSelect.value) {
                searchParams.append('start_time', startTimeSelect.value);
            }
            
            const endTimeSelect = document.querySelector('select[name="end_time"]');
            if (endTimeSelect && endTimeSelect.value) {
                searchParams.append('end_time', endTimeSelect.value);
            }
            
            // Construct URL with parameters
            let url = '/history/export-pdf';
            if (searchParams.toString()) {
                url += '?' + searchParams.toString();
            }
            
            // Create temporary link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = `rainfall_${type}_report.pdf`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
        
        // Function to print table based on PDF template
        window.printTable = function(tableId) {
            // Determine the report type based on the table ID
            let type = 'rainfall';
            if (tableId.includes('avg-day')) {
                type = 'average_daily';
            } else if (tableId.includes('avg-month')) {
                type = 'average_monthly';
            }
            
            // Get current filter parameters
            const searchParams = new URLSearchParams();
            
            // Get form filter parameters
            const typeSelect = document.querySelector('select[name="type"]');
            if (typeSelect && typeSelect.value) {
                searchParams.append('type', type);
            }
            
            const locationSelect = document.querySelector('select[name="location"]');
            if (locationSelect && locationSelect.value) {
                searchParams.append('location', locationSelect.value);
            }
            
            const monthSelect = document.querySelector('select[name="month"]');
            if (monthSelect && monthSelect.value) {
                searchParams.append('month', monthSelect.value);
            }
            
            const daySelect = document.querySelector('select[name="day"]');
            if (daySelect && daySelect.value) {
                searchParams.append('day', daySelect.value);
            }
            
            const yearSelect = document.querySelector('select[name="year"]');
            if (yearSelect && yearSelect.value) {
                searchParams.append('year', yearSelect.value);
            }
            
            const startTimeSelect = document.querySelector('select[name="start_time"]');
            if (startTimeSelect && startTimeSelect.value) {
                searchParams.append('start_time', startTimeSelect.value);
            }
            
            const endTimeSelect = document.querySelector('select[name="end_time"]');
            if (endTimeSelect && endTimeSelect.value) {
                searchParams.append('end_time', endTimeSelect.value);
            }
            
            // Construct URL with parameters
            let url = '/history/export-pdf';
            if (searchParams.toString()) {
                url += '?' + searchParams.toString();
            }
            
            // Open the PDF in a new window for printing
            window.open(url, '_blank');
        };
</script>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard - MDDRMO Rainfall Monitoring')
@section('page_heading', 'Dashboard')

@section('content')
<style>
  .wrap { max-width: 900px; margin: 0 auto; }
  h1 { font-size: 20px; margin: 0 0 12px; }
  .meta { font-size: 12px; color: #666; margin-bottom: 12px; }
  table { width: 100%; border-collapse: collapse; background: #fff; }
  th, td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: left; }
  th { background: #f8f8f8; font-weight: 600; }
  tbody tr:hover { background: #fafafa; }
  .status { margin: 10px 0; font-size: 13px; color: #444; }
  .spinner { display: inline-block; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #ccc; border-top-color: #333; animation: spin 0.8s linear infinite; vertical-align: -2px; }
  @keyframes spin { to { transform: rotate(360deg); } }
  .error { color: #b00020; }
  .muted { color: #777; }
</style>



@if(isset(Auth::user()->role) && strcasecmp(Auth::user()->role, 'Admin') === 0)

<!-- Statistics Cards Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-600 text-xs font-medium mb-1">Total Stations</p>
                <p class="text-xl font-semibold text-blue-600">{{ count($latestRainfall ?? []) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-broadcast-tower text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-600 text-xs font-medium mb-1">Active Stations</p>
                @php
                  $activeStationsCount = collect($latestRainfall ?? [])->filter(function($item) {
                    return ($item->cumulative_rainfall ?? 0) > 0;
                  })->count();
                @endphp
                <p class="text-xl font-semibold text-green-600">{{ $activeStationsCount }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tint text-green-600 text-lg"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-6">
        <div class="flex justify-between items-start">
            <div>
                @php
                  $highIntensityLocations = collect($latestRainfall ?? [])->filter(function($item) {
                    return ($item->intensity_level ?? '') === 'Torrential';
                  });
                  $alertCount = $highIntensityLocations->count();
                @endphp
                <p class="text-gray-600 text-xs font-medium mb-1">High Intensity Locations</p>
                @if($alertCount > 0)
                    <div class="text-sm font-semibold text-red-600 space-y-1" id="torrentialLocationsSlider">
                        @foreach($highIntensityLocations as $index => $location)
                            <div class="flex flex-col torrential-slide @if($index != 0) hidden @endif" data-index="{{ $index }}">
                                <span class="font-bold">{{ $location->dev_location ?? 'Unknown' }}</span>
                                <span class="text-xs text-red-500">{{ number_format($location->cumulative_rainfall ?? 0, 1) }} mm - {{ $location->intensity_level ?? 'Unknown' }}</span>
                            </div>
                        @endforeach
                        @if($alertCount > 1)
                            <div class="flex justify-center mt-2 space-x-1" id="sliderIndicators">
                                @foreach($highIntensityLocations as $index => $location)
                                    <span class="w-2 h-2 rounded-full @if($index == 0) bg-red-600 @else bg-red-200 @endif indicator-dot" data-index="{{ $index }}"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xl font-semibold text-purple-600">No Alerts</p>
                @endif
            </div>
            <div class="w-10 h-10 {{ $alertCount > 0 ? 'bg-red-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center">
                <i class="fas {{ $alertCount > 0 ? 'fa-exclamation-triangle text-red-600' : 'fa-check-circle text-purple-600' }} text-lg"></i>
            </div>
        </div>
    </div>
</div>

  
{{-- ✅ Rainfall Data Table --}}
<div class="bg-white rounded-lg shadow-md mt-6 border border-gray-200">
  <div class="bg-[#0a1d3a] text-white px-4 py-3 rounded-t">
    <h3 class="text-lg font-semibold">Automated Rain Gauge - Monitoring Table</h3>
  </div>
  
  <div class="overflow-x-auto">
    <table class="min-w-full border border-gray-200">
      <thead>
        <tr class="border-b border-gray-200">
          <th rowspan="2" class="px-4 py-3 text-center text-sm font-medium text-gray-700">Location</th>
          <th colspan="3" class="px-4 py-3 text-center text-sm font-medium text-gray-700 border-x border-gray-200">Accumulated Rainfall</th>
          <th rowspan="2" class="px-4 py-3 text-center text-sm font-medium text-gray-700">Intensity Level</th>
        </tr>
        <tr class="border-b border-gray-150">
          <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 border-x border-gray-200">1 Min</th>
          <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 border-x border-gray-200">15 Min</th>
          <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 border-x border-gray-200">30 Min</th>
        </tr>
      </thead>
      <tbody>
        @forelse($latestRainfall as $rainfall)
          <tr class="border-b border-gray-100 even:bg-gray-50">
            {{-- Location --}}
            <td class="px-4 py-3 text-center text-gray-900 border-r">{{ $rainfall->dev_location ?? 'Unknown' }}</td>
            
            {{-- Middle cells with borders --}}
            <td class="px-4 py-3 text-center text-gray-900 border-r ">
              {{ isset($rainfall->rainfall_1min) ? number_format($rainfall->rainfall_1min, 1) : '0.0' }}
            </td>
            <td class="px-4 py-3 text-center text-gray-900 border-r ">
              {{ isset($rainfall->rainfall_15min) ? number_format($rainfall->rainfall_15min, 1) : '0.0' }}
            </td>
            <td class="px-4 py-3 text-center text-gray-900 border-r ">
              {{ isset($rainfall->rainfall_30min) ? number_format($rainfall->rainfall_30min, 1) : '0.0' }}
            </td>
            
            {{-- Intensity Level --}}
            <td class="px-4 py-3 text-center">
              @php
                $intensity = $rainfall->intensity_level ?? 'Unknown';
                $colorClass = match($intensity) {
                    'Torrential' => 'bg-red-500 text-white',
                    'Intense' => 'bg-orange-400 text-white',
                    'Heavy' => 'bg-green-400 text-white',
                    'Moderate' => 'bg-blue-300 text-white',
                    'Unknown' => 'bg-gray-300 text-gray-800',
                    default => 'bg-gray-200 text-gray-800'
                };
              @endphp
              <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }} shadow-sm">
                {{ $intensity }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
              No rainfall data available
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


<!-- Map + Chart -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
  <!-- Map -->
  <div class="bg-white shadow-md rounded relative overflow-hidden">
    <div class="bg-[#172540] text-white px-6 py-3 rounded-t">
      <h3 class="text-lg font-semibold font-serif">Device Location Map</h3>
    </div>
    <div class="relative h-[600px] bg-white border border-gray-300">
      <div id="deviceMap" class="w-full h-full rounded-b"></div>
      <div id="legendToggle" class="absolute top-4 right-4 bg-blue-500 text-white rounded-lg shadow-lg px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-blue-600 z-[9999]">
        <i class="fas fa-layer-group mr-1"></i>Show Legend
      </div>
      <div id="rainLegend" class="hidden absolute top-4 right-4 z-[1100] bg-cyan-200 text-black rounded-lg shadow-lg border border-black px-4 py-3 w-[260px]">
        <div class="flex items-center justify-between mb-2">
          <h4 class="font-semibold text-sm">Rain Rate Classification</h4>
          <button id="closeLegend" type="button" class="text-xs px-2 py-0.5 rounded border border-gray-400 hover:bg-gray-100">Close</button>
        </div>
        <div class="grid grid-cols-3 gap-2 text-sm">
          <div class="font-medium">Rain Type</div><div class="font-medium">Intensity</div><div class="font-medium">Color</div>
          <div>Torrential</div><div>&gt;31</div><div><div class="w-5 h-4 bg-red-500 border rounded"></div></div>
          <div>Intense</div><div>16 - 30</div><div><div class="w-5 h-4 bg-orange-400 border rounded"></div></div>
          <div>Heavy</div><div>7.6 - 15</div><div><div class="w-5 h-4 bg-green-400 border rounded"></div></div>
          <div>Moderate</div><div>2.6 - 7.5</div><div><div class="w-5 h-4 bg-blue-300 border rounded"></div></div>
          <div>Light</div><div>0.01 - 2.5</div><div><div class="w-5 h-4 bg-gray-200 border rounded"></div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Functional Chart -->
  <div class="bg-white shadow-md rounded">
    <div class="bg-[#172540] text-white px-6 py-3 rounded-t">
      <h3 class="text-lg font-semibold font-serif">Rainfall Chart</h3>
    </div>
    <div class="p-6">
      <!-- Chart Controls -->
      <div class="mb-6 space-y-4">
        <div class="flex flex-wrap gap-4 items-center">
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Location:</label>
            <select id="chartLocation" class="text-sm border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:border-blue-400">
              <option value="">All Locations</option>
              @foreach($availableLocations ?? [] as $location)
                <option value="{{ $location }}">{{ $location }}</option>
              @endforeach
            </select>
          </div>
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Time Interval:</label>
            <select id="chartTimeInterval" class="text-sm border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:border-blue-400">
              <option value="10" selected>10 Minutes</option>
              <option value="20">20 Minutes</option>
              <option value="30">30 Minutes</option>
              <option value="60">1 Hour</option>
            </select>
          </div>
        </div>
        
        <!-- Quick Date Presets -->
        <div class="flex flex-wrap gap-2">
          <button type="button" class="date-preset bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-200 focus:outline-none focus:ring focus:border-gray-400" data-days="1">
            Last 24 Hours
          </button>
          <button type="button" class="date-preset bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-200 focus:outline-none focus:ring focus:border-gray-400" data-days="7">
            Last 7 Days
          </button>
          <button type="button" class="date-preset bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-200 focus:outline-none focus:ring focus:border-gray-400" data-days="30">
            Last 30 Days
          </button>
          <button type="button" class="date-preset bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-200 focus:outline-none focus:ring focus:border-gray-400" data-month="true">
            This Month
          </button>
        </div>
        
        <div class="flex flex-wrap gap-4 items-center">
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Date Range:</label>
            <input type="date" id="startDate" class="text-sm border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:border-blue-400" 
                   value="{{ now()->subDays(7)->format('Y-m-d') }}">
            <span class="text-gray-500">to</span>
            <input type="date" id="endDate" class="text-sm border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:border-blue-400" 
                   value="{{ now()->format('Y-m-d') }}">
          </div>
          <button id="updateChart" class="bg-blue-600 text-white text-sm px-4 py-1 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-400">
            Update Chart
          </button>
        </div>
        
        <div class="flex items-center gap-4 text-sm">
          <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-blue-500 rounded"></div>
            <span class="font-medium">Hourly Rainfall (mm)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-4 h-2 bg-red-500 rounded"></div>
            <span class="font-medium">Cumulative Rainfall (mm)</span>
          </div>
        </div>
        
        <!-- Data Aggregation Note -->
        <div class="text-xs text-gray-500 bg-gray-50 px-3 py-2 rounded">
          <i class="fas fa-info-circle mr-1"></i>
          Data is aggregated by <span id="currentInterval">10 minutes</span> for better chart readability. Each point represents the total rainfall and tips for that time interval.
        </div>
      </div>
      
      <!-- Chart Container -->
      <div class="relative h-[300px]">
        <div id="chartLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 hidden">
          <div class="text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
            <p class="text-sm text-gray-600">Loading chart data...</p>
          </div>
        </div>
        <canvas id="rainfallChart"></canvas>
      </div>
    </div>
  </div>
</section>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
  /* Pulsating concentric marker styles */
  .pulse-marker { position: relative; width: 22px; height: 22px; }
  .pulse-core { position: absolute; width: 22px; height: 22px; border-radius: 9999px; box-shadow: 0 0 0 2px rgba(0,0,0,0.08), 0 0 12px rgba(0,0,0,0.08); }
  .pulse-ring { position: absolute; left: 50%; top: 50%; width: 22px; height: 22px; transform: translate(-50%, -50%); border-radius: 9999px; opacity: 0.85; animation: pulse 1.1s ease-out infinite; }
  .pulse-ring.delay-1 { animation-delay: 0.3s; }
  .pulse-ring.delay-2 { animation-delay: 0.6s; }
  .pulse-ring.delay-3 { animation-delay: 0.9s; }
  @keyframes pulse { 0% { transform: translate(-50%, -50%) scale(1); opacity: 0.85; } 60% { opacity: 0.15; } 100% { transform: translate(-50%, -50%) scale(5.2); opacity: 0; } }
  /* Color themes per classification */
  .pulse-red .pulse-core { background-color: #ef4444; }
  .pulse-red .pulse-ring { background-color: rgba(239, 68, 68, 0.5); }
  .pulse-orange .pulse-core { background-color: #fb923c; }
  .pulse-orange .pulse-ring { background-color: rgba(251, 146, 60, 0.5); }
  .pulse-green .pulse-core { background-color: #34d399; }
  .pulse-green .pulse-ring { background-color: rgba(52, 211, 153, 0.5); }
  .pulse-blue .pulse-core { background-color: #60a5fa; }
  .pulse-blue .pulse-ring { background-color: rgba(96, 165, 250, 0.5); }
  .pulse-gray .pulse-core { background-color: #9ca3af; }
  .pulse-gray .pulse-ring { background-color: rgba(156, 163, 175, 0.5); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rainfallChart = null;
    let leafletMap = null;
    
    // Initialize Leaflet map with real rainfall data
    function initDeviceMap() {
        // Center near Tagoloan/Cagayan de Oro, Misamis Oriental (approx)
        leafletMap = L.map('deviceMap').setView([8.547, 124.764], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(leafletMap);

        // Get rainfall data from PHP variables
        const rainfallData = {!! json_encode($latestRainfall ?? []) !!};
        
        if (rainfallData.length === 0) {
            // If no data, show a message on the map
            const noDataDiv = document.createElement('div');
            noDataDiv.innerHTML = '<div class="text-center text-gray-500 p-4">No rainfall data available</div>';
            noDataDiv.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;';
            document.getElementById('deviceMap').appendChild(noDataDiv);
            return;
        }

        const group = L.featureGroup();
        
        // Define coordinates for known locations (you can expand this mapping)
        const locationCoordinates = {
            'Baluarte': [8.543044097249751, 124.74129245402983],
            'Casinglot': [8.51779192714334, 124.75483771860887],
            'Poblacion': [8.538456419790514, 124.75093172519458],
            'Sta Ana': [8.53293408299888, 124.79420716752404],
            'Natumulan': [8.535991071359792, 124.76750908101822]
        };

        rainfallData.forEach(rainfall => {
            const location = rainfall.dev_location || 'Unknown';
            const intensity = rainfall.intensity_level || 'Unknown';
            const rainfallAmount = rainfall.cumulative_rainfall || 0;
            const time = rainfall.created_at ? new Date(rainfall.created_at).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }) : 'N/A';
            
            // Get coordinates for the location, or use default if not found
            let coords = locationCoordinates[location];
            if (!coords) {
                // If location not in predefined coordinates, generate a random nearby coordinate
                const baseLat = 8.547;
                const baseLng = 124.764;
                const randomLat = baseLat + (Math.random() - 0.5) * 0.02; // ±0.01 degrees
                const randomLng = baseLng + (Math.random() - 0.5) * 0.02;
                coords = [randomLat, randomLng];
            }

            const cls = getPulseClass(intensity);
            const iconHtml = `
                <div class="pulse-marker ${cls}">
                    <div class="pulse-core"></div>
                    <div class="pulse-ring"></div>
                    <div class="pulse-ring delay-1"></div>
                    <div class="pulse-ring delay-2"></div>
                    <div class="pulse-ring delay-3"></div>
                </div>`;

            const icon = L.divIcon({ html: iconHtml, className: 'pulse-wrapper', iconSize: [22, 22], iconAnchor: [11, 11] });
            
            const marker = L.marker(coords, { icon }).bindPopup(`
                <div class="text-sm">
                    <div class="font-semibold text-gray-800">${location}</div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Intensity:</span> 
                        <span class="font-semibold ${getIntensityTextColor(intensity)}">${intensity}</span>
                    </div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Rainfall:</span> 
                        <span class="font-semibold text-blue-600">${rainfallAmount} mm</span>
                    </div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Time:</span> 
                        <span class="font-semibold text-gray-600">${time}</span>
                    </div>
                </div>`);
            
            marker.addTo(group);
        });

        group.addTo(leafletMap);
        
        // Fit map to show all markers
        const bounds = group.getBounds();
        if (bounds.isValid()) {
            leafletMap.fitBounds(bounds.pad(0.3));
        }

        // Ensure map fits container after layout settles and on resize
        setTimeout(() => {
            leafletMap.invalidateSize();
            if (bounds.isValid()) leafletMap.fitBounds(bounds.pad(0.3));
        }, 250);
        window.addEventListener('resize', () => {
            leafletMap.invalidateSize();
            if (bounds.isValid()) leafletMap.fitBounds(bounds.pad(0.3));
        });
    }

    function getPulseClass(intensity) {
        switch (intensity) {
            case 'Torrential': return 'pulse-red';
            case 'Intense': return 'pulse-orange';
            case 'Heavy': return 'pulse-green';
            case 'Moderate': return 'pulse-blue';
            case 'Light': return 'pulse-gray';
            default: return 'pulse-gray';
        }
    }

    function getIntensityTextColor(intensity) {
        switch (intensity) {
            case 'Torrential': return 'text-red-600';
            case 'Intense': return 'text-orange-600';
            case 'Heavy': return 'text-green-600';
            case 'Moderate': return 'text-blue-600';
            case 'Light': return 'text-gray-600';
            default: return 'text-gray-600';
        }
    }

    // Initialize chart
    function initChart() {
        const ctx = document.getElementById('rainfallChart').getContext('2d');
        
        rainfallChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['No data available'],
                datasets: [
                    {
                        label: 'Hourly Rainfall (mm)',
                        data: [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderSkipped: false,
                        borderRadius: 4,
                        yAxisID: 'y1',
                        order: 2
                    },
                    {
                        label: 'Cumulative Rainfall (mm)',
                        data: [0],
                        type: 'line',
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Hourly Rainfall Data (Blue Bars: Hourly, Red Line: Cumulative)',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#374151'
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            title: function(context) {
                                return 'Time: ' + context[0].label;
                            },
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    // Show positive value for inverted bars
                                    return 'Hourly Rainfall: ' + Math.abs(context.parsed.y).toFixed(2) + ' mm';
                                } else {
                                    return 'Cumulative Rainfall: ' + context.parsed.y.toFixed(2) + ' mm';
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date & Hour',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Cumulative Rainfall (mm)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Hourly Rainfall (mm)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                // Convert negative values back to positive for display
                                return Math.abs(value).toFixed(1);
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: 'rgba(255, 255, 255, 0.8)',
                        hoverBorderColor: 'rgba(0, 0, 0, 0.8)',
                        hoverBorderWidth: 2
                    }
                }
            }
        });
    }
    
    // Load chart data
    function loadChartData() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const location = document.getElementById('chartLocation').value;
        const interval = document.getElementById('chartTimeInterval').value;
        
        // Show loading state
        document.getElementById('chartLoading').classList.remove('hidden');
        
        if (rainfallChart) {
            rainfallChart.data.labels = [];
            rainfallChart.data.datasets[0].data = [];
            rainfallChart.data.datasets[1].data = [];
            rainfallChart.update();
        }
        
        // Fetch data from API with cache-busting
        const timestamp = Date.now();
        const url = `/chart-data?start_date=${startDate}&end_date=${endDate}&location=${location}&interval=${interval}&_t=${timestamp}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Hide loading indicator
                document.getElementById('chartLoading').classList.add('hidden');
                
                if (rainfallChart) {
                    if (data.length === 0) {
                        // No data available
                        rainfallChart.data.labels = ['No data available for selected range'];
                        rainfallChart.data.datasets[0].data = [0];
                        rainfallChart.data.datasets[1].data = [0];
                        rainfallChart.update();
                        return;
                    }
                    
                    // Process hourly aggregated data for chart
                    const labels = data.map(item => {
                        if (item.minute_group !== undefined) {
                            // Handle minute-level intervals (10, 20, 30 minutes)
                            const date = new Date(item.date + ' ' + item.hour + ':' + item.minute_group.toString().padStart(2, '0') + ':00');
                            return date.toLocaleDateString() + ' ' + date.getHours().toString().padStart(2, '0') + ':' + item.minute_group.toString().padStart(2, '0');
                        } else {
                            // Handle hourly intervals
                            const date = new Date(item.date + ' ' + item.hour + ':00:00');
                            return date.toLocaleDateString() + ' ' + date.getHours().toString().padStart(2, '0') + ':00';
                        }
                    });
                    
                    // Calculate hourly rainfall and cumulative rainfall
                    const hourlyRainfall = data.map(item => parseFloat(item.total_rainfall) || 0);
                    let cumulativeRainfall = [];
                    let runningTotal = 0;
                    
                    hourlyRainfall.forEach(amount => {
                        runningTotal += amount;
                        cumulativeRainfall.push(runningTotal);
                    });
                    
                    // Invert hourly rainfall values for upside-down bars
                    const invertedHourlyRainfall = hourlyRainfall.map(value => -value);
                    
                    // Update chart
                    rainfallChart.data.labels = labels;
                    rainfallChart.data.datasets[0].data = invertedHourlyRainfall; // Blue bars (hourly, inverted)
                    rainfallChart.data.datasets[1].data = cumulativeRainfall; // Red line (cumulative)
                    rainfallChart.update();
                }
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                // Hide loading indicator
                document.getElementById('chartLoading').classList.add('hidden');
                
                // Show error message
                if (rainfallChart) {
                    rainfallChart.data.labels = ['Error loading data'];
                    rainfallChart.data.datasets[0].data = [0];
                    rainfallChart.data.datasets[1].data = [0];
                    rainfallChart.update();
                }
            });
    }
    
    // Initialize chart and load initial data
    initChart();
    loadChartData();
    initDeviceMap();
    // Legend toggle functionality (show/hide)
    const legendToggle = document.getElementById('legendToggle');
    const rainLegend = document.getElementById('rainLegend');
    const closeLegend = document.getElementById('closeLegend');

    if (legendToggle && rainLegend) {
        legendToggle.addEventListener('click', function() {
            rainLegend.classList.remove('hidden');
            legendToggle.classList.add('hidden');
        });
    }
    if (closeLegend && rainLegend && legendToggle) {
        closeLegend.addEventListener('click', function() {
            rainLegend.classList.add('hidden');
            legendToggle.classList.remove('hidden');
        });
    }
    
    // Function to refresh dashboard data
    function refreshDashboard() {
        console.log('Refreshing dashboard data...');
        
        // Show refresh indicator
        document.getElementById('refreshIndicator').classList.remove('hidden');
        document.getElementById('tableRefreshIndicator').classList.remove('hidden');
        
        // Add cache-busting parameter to ensure fresh data
        const timestamp = Date.now();
        const url = `/table-data?_t=${timestamp}`;
        
        // Fetch updated table data
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Received table data:', data);
                console.log('Data count:', data.dataCount);
                console.log('Server time:', data.serverTime);
                console.log('Cache buster:', data.cacheBuster);
                
                // Update active stations count
                const activeStationsElement = document.getElementById('activeStationsCount');
                if (activeStationsElement) {
                    activeStationsElement.textContent = data.activeStations;
                }
                
                // Update high intensity alerts
                updateHighIntensityAlerts(data.highIntensityAlerts);
                
                // Update monitoring table
                updateMonitoringTable(data.latestRainfall);
                
                // Update map with new data
                updateDeviceMap(data.latestRainfall);
                
                // Update timestamp with more detail
                const lastUpdateElement = document.getElementById('lastUpdate');
                if (lastUpdateElement) {
                    const now = new Date();
                    const localTime = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    lastUpdateElement.textContent = `Last updated: ${localTime}`;
                }
                
                // Update data count
                const dataCountElement = document.getElementById('dataCount');
                if (dataCountElement) {
                    dataCountElement.textContent = `(${data.latestRainfall.length} stations)`;
                }
                
                // Update table timestamp
                const tableLastUpdateElement = document.getElementById('tableLastUpdate');
                if (tableLastUpdateElement) {
                    const now = new Date();
                    const localTime = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    tableLastUpdateElement.textContent = `Last: ${localTime}`;
                }
                
                // Hide refresh indicators
                document.getElementById('refreshIndicator').classList.add('hidden');
                document.getElementById('tableRefreshIndicator').classList.add('hidden');
                
                console.log('Dashboard refresh completed');
            })
            .catch(error => {
                console.error('Error refreshing dashboard data:', error);
                // Hide refresh indicators
                document.getElementById('refreshIndicator').classList.add('hidden');
                document.getElementById('tableRefreshIndicator').classList.add('hidden');
            });
    }
    
    // Function to update high intensity alerts
    function updateHighIntensityAlerts(alerts) {
        console.log('Updating alerts:', alerts);
        const alertsContainer = document.querySelector('.flex.items-center.gap-3');
        if (!alertsContainer) {
            console.error('Alerts container not found');
            return;
        }
        
        // Find the alerts section (the div containing the alerts)
        const alertsSection = alertsContainer.querySelector('div:last-child');
        if (!alertsSection) {
            console.error('Alerts section not found');
            return;
        }
        
        if (alerts.length === 0) {
            alertsSection.innerHTML = `
                <div class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full flex items-center gap-2">
                    <i class="fas fa-info-circle text-gray-700 text-sm"></i>
                    <span>No high intensity alerts</span>
                </div>
            `;
        } else {
            alertsSection.innerHTML = alerts.map(alert => `
                <div class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-red-700 text-sm"></i>
                    <span>${alert.dev_location}</span>
                </div>
                <div class="bg-orange-100 text-orange-800 text-xs px-3 py-1 rounded-full flex items-center gap-2">
                    <i class="fas fa-tint text-orange-700 text-sm"></i>
                    <span>${alert.intensity_level}</span>
                </div>
            `).join('');
        }
    }
    
    // Function to update monitoring table
    function updateMonitoringTable(rainfallData) {
        console.log('Updating table with data:', rainfallData);
        const tbody = document.querySelector('#monitoring-table tbody');
        if (!tbody) {
            console.error('Table tbody not found');
            return;
        }
        
        if (rainfallData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        No rainfall data available
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = rainfallData.map(rainfall => {
                const intensity = rainfall.intensity_level || 'Unknown';
                const colorClass = getIntensityColorClass(intensity);
                
                // Better time formatting with proper parsing
                let timeDisplay = 'N/A';
                if (rainfall.created_at) {
                    try {
                        const date = new Date(rainfall.created_at);
                        if (!isNaN(date.getTime())) {
                            timeDisplay = date.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing date:', rainfall.created_at, e);
                    }
                }
                
                return `
                    <tr class="table-row-update">
                        <td class="px-4 py-2">${rainfall.dev_location || 'Unknown'}</td>
                        <td class="px-4 py-2">${timeDisplay}</td>
                        <td class="px-4 py-2">${rainfall.cumulative_rainfall ? (parseFloat(rainfall.cumulative_rainfall).toFixed(1) + ' mm') : 'N/A'}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium ${colorClass}">
                                ${intensity}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Add highlight effect to show data was updated
            const rows = tbody.querySelectorAll('.table-row-update');
            rows.forEach(row => {
                row.style.backgroundColor = '#fef3c7';
                setTimeout(() => {
                    row.style.backgroundColor = '';
                    row.classList.remove('table-row-update');
                }, 1000);
            });
            
            // Update the map with new data
            updateDeviceMap(rainfallData);
        }
    }
    
    // Function to update device map with new data
    function updateDeviceMap(rainfallData) {
        if (!leafletMap) return;
        
        // Clear existing markers
        leafletMap.eachLayer((layer) => {
            if (layer instanceof L.Marker) {
                leafletMap.removeLayer(layer);
            }
        });
        
        if (rainfallData.length === 0) {
            // Show no data message
            const noDataDiv = document.createElement('div');
            noDataDiv.innerHTML = '<div class="text-center text-gray-500 p-4">No rainfall data available</div>';
            noDataDiv.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;';
            document.getElementById('deviceMap').appendChild(noDataDiv);
            return;
        }
        
        const group = L.featureGroup();
        
        // Define coordinates for known locations (you can expand this mapping)
        const locationCoordinates = {
            'Baluarte': [8.543044097249751, 124.74129245402983],
            'Casinglot': [8.51779192714334, 124.75483771860887],
            'Poblacion': [8.538456419790514, 124.75093172519458],
            'Sta Ana': [8.53293408299888, 124.79420716752404],
            'Natumulan': [8.535991071359792, 124.76750908101822]
        };
        
        rainfallData.forEach(rainfall => {
            const location = rainfall.dev_location || 'Unknown';
            const intensity = rainfall.intensity_level || 'Unknown';
            const rainfallAmount = rainfall.cumulative_rainfall || 0;
            const time = rainfall.created_at ? new Date(rainfall.created_at).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }) : 'N/A';
            
            // Get coordinates for the location, or use default if not found
            let coords = locationCoordinates[location];
            if (!coords) {
                // If location not in predefined coordinates, generate a random nearby coordinate
                const baseLat = 8.547;
                const baseLng = 124.764;
                const randomLat = baseLat + (Math.random() - 0.5) * 0.02; // ±0.01 degrees
                const randomLng = baseLng + (Math.random() - 0.5) * 0.02;
                coords = [randomLat, randomLng];
            }
            
            const cls = getPulseClass(intensity);
            const iconHtml = `
                <div class="pulse-marker ${cls}">
                    <div class="pulse-core"></div>
                    <div class="pulse-ring"></div>
                    <div class="pulse-ring delay-1"></div>
                    <div class="pulse-ring delay-2"></div>
                    <div class="pulse-ring delay-3"></div>
                </div>`;
            
            const icon = L.divIcon({ html: iconHtml, className: 'pulse-wrapper', iconSize: [22, 22], iconAnchor: [11, 11] });
            
            const marker = L.marker(coords, { icon }).bindPopup(`
                <div class="text-sm">
                    <div class="font-semibold text-gray-800">${location}</div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Intensity:</span> 
                        <span class="font-semibold ${getIntensityTextColor(intensity)}">${intensity}</span>
                    </div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Rainfall:</span> 
                        <span class="font-semibold text-blue-600">${rainfallAmount} mm</span>
                    </div>
                    <div class="mt-1">
                        <span class="font-medium text-gray-700">Time:</span> 
                        <span class="font-semibold text-gray-600">${time}</span>
                    </div>
                </div>`);
            
            marker.addTo(group);
        });
        
        group.addTo(leafletMap);
        
        // Fit map to show all markers
        const bounds = group.getBounds();
        if (bounds.isValid()) {
            leafletMap.fitBounds(bounds.pad(0.3));
        }
    }
    
    // Function to update chart title and description based on time interval
    function updateChartIntervalDisplay() {
        const interval = document.getElementById('chartTimeInterval').value;
        const intervalText = interval == 10 ? '10 minutes' : 
                           interval == 20 ? '20 minutes' : 
                           interval == 30 ? '30 minutes' : '1 hour';
        
        document.getElementById('currentInterval').textContent = intervalText;
        
        // Update chart title
        if (rainfallChart) {
            rainfallChart.options.plugins.title.text = `${intervalText.charAt(0).toUpperCase() + intervalText.slice(1)} Rainfall Data`;
            rainfallChart.update();
        }
    }
    
    // Helper function to get intensity color class
    function getIntensityColorClass(intensity) {
        switch(intensity) {
            case 'Torrential': return 'bg-red-500 text-white';
            case 'Intense': return 'bg-orange-400 text-white';
            case 'Heavy': return 'bg-green-400 text-white';
            case 'Moderate': return 'bg-blue-300 text-white';
            case 'Unknown': return 'bg-gray-300 text-gray-800';
            default: return 'bg-gray-200 text-gray-800';
        }
    }
    
    // Event listeners
    document.getElementById('updateChart').addEventListener('click', loadChartData);
    document.getElementById('chartTimeInterval').addEventListener('change', updateChartIntervalDisplay);
    document.getElementById('manualRefresh').addEventListener('click', refreshDashboard);
    document.getElementById('testNewData').addEventListener('click', testNewData);
    document.getElementById('forceRefresh').addEventListener('click', forceRefreshWithTestData);
    
    // Function to test new data (simulate Arduino sending data)
    function testNewData() {
        console.log('Testing new data...');
        
        // Simulate sending new rainfall data
        const testData = {
            rain_tips: Math.floor(Math.random() * 10) + 1,
            dev_id: 'TEST001',
            dev_location: 'Test Location'
        };
        
        fetch('/rain?' + new URLSearchParams(testData))
            .then(response => response.text())
            .then(result => {
                console.log('Test data sent:', result);
                // Refresh the dashboard to show new data
                setTimeout(() => {
                    refreshDashboard();
                }, 1000);
            })
            .catch(error => {
                console.error('Error sending test data:', error);
            });
    }
    
    // Function to force refresh with test data
    function forceRefreshWithTestData() {
        console.log('Force refreshing with test data...');
        
        // Create test data with current timestamp
        const testData = [
            {
                dev_location: 'Test Station 1',
                rain_tips: Math.floor(Math.random() * 10) + 1,
                cumulative_rainfall: (Math.random() * 5 + 0.5).toFixed(1),
                intensity_level: 'Moderate',
                created_at: new Date().toISOString()
            },
            {
                dev_location: 'Test Station 2',
                rain_tips: Math.floor(Math.random() * 10) + 1,
                cumulative_rainfall: (Math.random() * 5 + 0.5).toFixed(1),
                intensity_level: 'Light',
                created_at: new Date().toISOString()
            }
        ];
        
        // Update the table directly with test data
        updateMonitoringTable(testData);
        
        // Update timestamps
        const now = new Date();
        const localTime = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        const lastUpdateElement = document.getElementById('lastUpdate');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = `Last updated: ${localTime}`;
        }
        
        const tableLastUpdateElement = document.getElementById('tableLastUpdate');
        if (tableLastUpdateElement) {
            tableLastUpdateElement.textContent = `Last: ${localTime}`;
        }
        
        console.log('Force refresh completed with test data');
    }
    
    // Date preset buttons
    document.querySelectorAll('.date-preset').forEach(button => {
        button.addEventListener('click', function() {
            const days = this.dataset.days;
            const month = this.dataset.month;
            
            let startDate, endDate = new Date();
            
            if (month) {
                // This month
                startDate = new Date(endDate.getFullYear(), endDate.getMonth(), 1);
            } else if (days) {
                // Last N days
                startDate = new Date();
                startDate.setDate(endDate.getDate() - parseInt(days));
            }
            
            // Update date inputs
            document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
            document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
            
            // Update chart automatically
            loadChartData();
        });
    });
    
    // Auto-refresh every 30 seconds
    // setInterval(loadChartData, 30 * 1000);
    // Auto-refresh dashboard every 30 seconds
    // setInterval(refreshDashboard, 30 * 1000);
});
</script>

@elseif(isset(Auth::user()->role) && strcasecmp(Auth::user()->role, 'Staff') === 0)
    <h1>Hi, I'm a staff</h1>
@else
    <p class="text-red-700">No Assign Role</p>
@endif

</div>
@endsection

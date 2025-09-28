<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RainfallController extends Controller
{
    private const TIP_TO_MM_RATIO = 0.2;
    private const INTENSITY_THRESHOLDS = [
        'Torrential' => 31,
        'Intense' => 16,
        'Heavy' => 7.6,
        'Moderate' => 2.6,
    ];

    public function storeFromArduino(Request $request)
    {
        $serial = $request->query('serial_number');
        $rainTips = (int) $request->query('rain_tips', 0);
        $incomingLocation = trim($request->query('dev_location', ''));

        if (empty($serial)) {
            return response("Missing serial number", 400);
        }
        
        $device = DB::table('devices')->where('serial_number', $serial)->first();
        if (!$device) {
            return response("Device not registered", 404);
        }

        if (!$device->dev_id) {
            Log::error("Device found but has no dev_id. Serial number: " . $serial);
            return response("Invalid device configuration", 400);
        }

        // Resolve location
        $devLocation = (!empty($incomingLocation) && strcasecmp($incomingLocation, 'Unknown') !== 0)
            ? $incomingLocation
            : ($device->dev_location ?? 'Unknown');

        // Auto-activate pending devices
        $updateData = ['updated_at' => now()];
        if ($device->status === 'pending') {
            $updateData['status'] = 'active';
            $updateData['dev_location'] = $devLocation;
        }
        
        DB::table('devices')->where('serial_number', $serial)->update($updateData);

        // Store rainfall data
        $cumulativeRainfall = $rainTips * self::TIP_TO_MM_RATIO;
        
        DB::table('rainfalls')->insert([
            'device_id' => $device->dev_id,
            'dev_location' => $devLocation,
            'rain_tips' => $rainTips,
            'cumulative_rainfall' => $cumulativeRainfall,
            'intensity_level' => $this->computeIntensity($cumulativeRainfall),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response("OK", 200);
    }

    public function dashboard()
    {
        $activeStations = DB::table('devices')->where('status', 'active')->count();

        $latestRainfall = DB::table('rainfalls')
            ->select('dev_location', 'rain_tips', 'cumulative_rainfall', 'intensity_level', 'created_at')
            ->whereNotNull('dev_location')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('dev_location')
            ->map(fn($group) => $group->first())
            ->values();

        $highIntensityAlerts = DB::table('rainfalls')
            ->select('dev_location', 'intensity_level', 'cumulative_rainfall')
            ->whereIn('intensity_level', ['Torrential', 'Intense', 'Heavy'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $availableLocations = DB::table('rainfalls')
            ->distinct()
            ->whereNotNull('dev_location')
            ->pluck('dev_location');

        return view('dashboard', compact(
            'activeStations', 
            'latestRainfall', 
            'highIntensityAlerts', 
            'availableLocations'
        ));
    }

    public function getChartData(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $location = $request->input('location');
        $interval = (int) $request->input('interval', 60);

        $query = $this->buildChartQuery($interval);
        
        $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($location) {
            $query->where('dev_location', $location);
        }

        return response()->json(
            $query->orderBy('date')->orderBy('hour')->get()
        );
    }

    public function getTableData()
    {
        $activeStations = DB::table('devices')->where('status', 'active')->count();

        $latestRainfall = DB::table('rainfalls')
            ->select('dev_location', 'rain_tips', 'cumulative_rainfall', 'intensity_level', 'created_at')
            ->whereNotNull('dev_location')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('dev_location')
            ->map(fn($group) => $group->first())
            ->values();

        $highIntensityAlerts = DB::table('rainfalls')
            ->select('dev_location', 'intensity_level', 'cumulative_rainfall')
            ->whereIn('intensity_level', ['Torrential', 'Intense', 'Heavy'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'activeStations' => $activeStations,
            'latestRainfall' => $latestRainfall,
            'highIntensityAlerts' => $highIntensityAlerts,
            'dataCount' => $latestRainfall->count(),
            'serverTime' => now()->toISOString(),
        ]);
    }

    private function buildChartQuery(int $interval)
    {
        $selectFields = [
            'dev_location',
            DB::raw('DATE(created_at) as date'),
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('SUM(cumulative_rainfall) as total_rainfall'),
            DB::raw('SUM(rain_tips) as total_tips'),
            DB::raw('MAX(intensity_level) as max_intensity'),
            DB::raw('COUNT(*) as readings_count')
        ];

        $groupByFields = ['dev_location', DB::raw('DATE(created_at)'), DB::raw('HOUR(created_at)')];

        if (in_array($interval, [10, 20, 30])) {
            $selectFields[] = DB::raw("FLOOR(MINUTE(created_at) / {$interval}) * {$interval} as minute_group");
            $groupByFields[] = DB::raw("FLOOR(MINUTE(created_at) / {$interval}) * {$interval}");
        }

        return DB::table('rainfalls')
            ->select($selectFields)
            ->groupBy($groupByFields);
    }

    private function computeIntensity(float $mm): string
    {
        foreach (self::INTENSITY_THRESHOLDS as $level => $threshold) {
            if ($mm > $threshold) {
                return $level;
            }
        }
        return $mm >= 0.01 ? 'Light' : 'No Rain';
    }
}
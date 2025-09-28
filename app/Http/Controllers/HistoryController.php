<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\PdfDownloadLoggerService;

class HistoryController extends Controller
{
    private const REPORT_TYPES = [
        'Rainfall',
        'Average Rainfall per day',
        'Average Rainfall per month'
    ];

    public function index(Request $request)
    {
        $selectedType = $request->input('type', 'Rainfall');
        
        if (!in_array($selectedType, self::REPORT_TYPES)) {
            $selectedType = 'Rainfall';
        }

        try {
            $data = $this->getReportData($selectedType, $request);
            $statistics = $this->calculateStatistics();
            $locations = $this->getAvailableLocations();

            return view('history', array_merge($data, [
                'selectedType' => $selectedType,
                'locations' => $locations,
                'statistics' => $statistics
            ]));

        } catch (\Exception $e) {
            Log::error('Error in history controller: ' . $e->getMessage());
            return view('history', $this->getEmptyData($selectedType));
        }
    }

    public function exportRainfallPdf(Request $request)
    {
        try {
            $type = $request->input('type', 'rainfall');
            $filters = $this->buildFilters($request);
            
            $data = $this->getPdfData($type, $request);
            $summary = $this->calculateSummary($data, $type);

            $pdfData = [
                'title' => $this->getPdfTitle($type),
                'type' => $type,
                'data' => $data,
                'filters' => $filters,
                'summary' => $summary
            ];

            // Log PDF download
            (new PdfDownloadLoggerService())->logPdfDownload(
                PdfDownloadLoggerService::getReportTypes()['rainfall'],
                $request->only(['month', 'day', 'year', 'location', 'start_time', 'end_time'])
            );

            $pdf = app('dompdf.wrapper')->loadView('pdf.rainfall', $pdfData)
                ->setPaper('a4', 'landscape');

            $filename = strtolower(str_replace(' ', '_', $this->getPdfTitle($type))) . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating rainfall PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function getReportData(string $type, Request $request): array
    {
        switch ($type) {
            case 'Rainfall':
                return $this->getRainfallData($request);
            case 'Average Rainfall per day':
                return $this->getDailyAverageData($request);
            case 'Average Rainfall per month':
                return $this->getMonthlyAverageData($request);
            default:
                return $this->getEmptyData($type);
        }
    }

    private function getRainfallData(Request $request): array
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('DAY(r.created_at) as day'),
                DB::raw('YEAR(r.created_at) as year'),
                'r.created_at',
                'r.rain_tips as tip_count',
                'r.cumulative_rainfall',
                'r.intensity_level'
            ]);

        $this->applyFilters($query, $request);
        
        return [
            'rainfallData' => $query->orderBy('r.created_at', 'desc')->paginate(15)->appends(request()->query()),
            'averagePerDay' => [],
            'averagePerMonth' => [],
            'detailDayData' => $this->getDetailDayData($request),
            'detailMonthData' => $this->getDetailMonthData($request),
        ];
    }

    private function getDailyAverageData(Request $request): array
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('DATE(r.created_at) as date'),
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('DAY(r.created_at) as day'),
                DB::raw('YEAR(r.created_at) as year'),
                DB::raw('ROUND(AVG(r.cumulative_rainfall), 2) as average_rainfall'),
                DB::raw('ROUND(SUM(r.rain_tips), 2) as total_tips'),
                DB::raw('ROUND(SUM(r.cumulative_rainfall), 2) as total_rainfall')
            ]);

        $this->applyFilters($query, $request);
        
        return [
            'rainfallData' => [],
            'averagePerDay' => $query
                ->groupBy('d.dev_id', 'r.dev_location', DB::raw('DATE(r.created_at)'), DB::raw('MONTH(r.created_at)'), DB::raw('DAY(r.created_at)'), DB::raw('YEAR(r.created_at)'))
                ->orderBy(DB::raw('DATE(r.created_at)'), 'desc')
                ->paginate(15)->appends(request()->query()),
            'averagePerMonth' => [],
            'detailDayData' => [],
            'detailMonthData' => [],
        ];
    }

    private function getMonthlyAverageData(Request $request): array
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('YEAR(r.created_at) as year'),
                DB::raw('ROUND(AVG(r.cumulative_rainfall), 2) as average_rainfall'),
                DB::raw('SUM(r.rain_tips) as total_tips'),
                DB::raw('ROUND(SUM(r.cumulative_rainfall), 2) as total_rainfall')
            ]);

        $this->applyFilters($query, $request);
        
        return [
            'rainfallData' => [],
            'averagePerDay' => [],
            'averagePerMonth' => $query
                ->groupBy('d.dev_id', 'r.dev_location', DB::raw('MONTH(r.created_at)'), DB::raw('YEAR(r.created_at)'))
                ->orderBy(DB::raw('YEAR(r.created_at)'), 'desc')
                ->orderBy(DB::raw('MONTH(r.created_at)'), 'desc')
                ->paginate(15)->appends(request()->query()),
            'detailDayData' => [],
            'detailMonthData' => [],
        ];
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('month')) {
            $query->whereMonth('r.created_at', (int)$request->month);
        }
        if ($request->filled('day')) {
            $query->whereDay('r.created_at', (int)$request->day);
        }
        if ($request->filled('year')) {
            $query->whereYear('r.created_at', (int)$request->year);
        }
        if ($request->filled('location')) {
            $query->where('r.dev_location', $request->location);
        }
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $start = $request->start_time;
            $end = $request->end_time;
            if ($end < $start) {
                [$start, $end] = [$end, $start];
            }
            $query->whereTime('r.created_at', '>=', $start)
                  ->whereTime('r.created_at', '<=', $end);
        } elseif ($request->filled('start_time')) {
            $query->whereTime('r.created_at', '>=', $request->start_time);
        } elseif ($request->filled('end_time')) {
            $query->whereTime('r.created_at', '<=', $request->end_time);
        }
    }

    private function getDetailDayData(Request $request): array
    {
        if (!$request->filled('detail_day') || !$request->filled('dev_location')) {
            return [];
        }

        $hourlyData = DB::table('rainfalls as r')
            ->select([
                DB::raw('HOUR(r.created_at) as hour'),
                DB::raw('SUM(r.cumulative_rainfall) as rainfall'),
                DB::raw('SUM(r.rain_tips) as tips'),
                DB::raw('MIN(r.created_at) as first_record'),
                DB::raw('MAX(r.created_at) as last_record')
            ])
            ->whereDate('r.created_at', $request->detail_day)
            ->where('r.dev_location', $request->dev_location)
            ->groupBy(DB::raw('HOUR(r.created_at)'))
            ->get()
            ->keyBy('hour');

        $hours = [];
        for ($h = 0; $h < 24; $h++) {
            $hourData = $hourlyData->get($h);
            $hours[$h] = [
                'rainfall' => $hourData ? (float)$hourData->rainfall : 0.0,
                'tips' => $hourData ? (int)$hourData->tips : 0,
                'start_time' => sprintf('%02d:00:00', $h),
                'end_time' => sprintf('%02d:00:00', $h + 1),
                'intensity_level' => $hourData ? $this->calculateIntensityLevel((float)$hourData->rainfall) : 'No Rain'
            ];
        }

        return [
            'date' => $request->detail_day,
            'dev_location' => $request->dev_location,
            'hours' => $hours,
            'total_tips' => $hourlyData->sum('tips'),
        ];
    }

    private function getDetailMonthData(Request $request): array
    {
        if (!$request->filled('detail_month') || !$request->filled('detail_year') || !$request->filled('dev_location')) {
            return [];
        }

        $month = (int)$request->detail_month;
        $year = (int)$request->detail_year;

        $dailyData = DB::table('rainfalls as r')
            ->select([
                DB::raw('DAY(r.created_at) as day'),
                DB::raw('SUM(r.cumulative_rainfall) as rainfall'),
                DB::raw('SUM(r.rain_tips) as tips')
            ])
            ->whereMonth('r.created_at', $month)
            ->whereYear('r.created_at', $year)
            ->where('r.dev_location', $request->dev_location)
            ->groupBy(DB::raw('DAY(r.created_at)'))
            ->get()
            ->keyBy('day');

        $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = [];
        
        for ($d = 1; $d <= $numDays; $d++) {
            $dayData = $dailyData->get($d);
            $days[$d] = [
                'rainfall' => $dayData ? (float)$dayData->rainfall : 0.0,
                'tips' => $dayData ? (int)$dayData->tips : 0,
                'intensity_level' => $dayData ? $this->calculateIntensityLevel((float)$dayData->rainfall) : 'No Rain'
            ];
        }

        return [
            'month' => $month,
            'year' => $year,
            'dev_location' => $request->dev_location,
            'days' => $days,
            'total_tips' => $dailyData->sum('tips'),
        ];
    }

    private function calculateStatistics(): array
    {
        return [
            'totalRainfall' => round(DB::table('rainfalls')->sum('cumulative_rainfall'), 2),
            'avgRainfall' => round(DB::table('rainfalls')->avg('cumulative_rainfall'), 2),
            'activeStations' => DB::table('devices')->distinct('dev_location')->count('dev_location')
        ];
    }

    private function getAvailableLocations(): array
    {
        return DB::table('rainfalls')
            ->select('dev_location')
            ->whereNotNull('dev_location')
            ->where('dev_location', '!=', '')
            ->where('dev_location', '!=', 'Unknown')
            ->distinct()
            ->orderBy('dev_location')
            ->pluck('dev_location')
            ->toArray();
    }

    private function calculateIntensityLevel(float $rainfall): string
    {
        if ($rainfall > 31) return 'Torrential';
        if ($rainfall >= 16) return 'Intense';
        if ($rainfall >= 7.6) return 'Heavy';
        if ($rainfall >= 2.6) return 'Moderate';
        if ($rainfall >= 0.01) return 'Light';
        return 'No Rain';
    }

    private function getEmptyData(string $selectedType): array
    {
        return [
            'selectedType' => $selectedType,
            'rainfallData' => [],
            'averagePerDay' => [],
            'averagePerMonth' => [],
            'detailDayData' => [],
            'detailMonthData' => [],
            'locations' => [],
            'statistics' => [
                'totalRainfall' => 0,
                'avgRainfall' => 0,
                'activeStations' => 0
            ]
        ];
    }

    private function buildFilters(Request $request): array
    {
        $filters = [];
        if ($request->month) $filters['Month'] = date('F', mktime(0, 0, 0, $request->month, 1));
        if ($request->day) $filters['Day'] = $request->day;
        if ($request->year) $filters['Year'] = $request->year;
        if ($request->start_time) $filters['Start Time'] = $request->start_time;
        if ($request->end_time) $filters['End Time'] = $request->end_time;
        if ($request->location) $filters['Location'] = $request->location;
        return $filters;
    }

    private function getPdfTitle(string $type): string
    {
        $titles = [
            'rainfall' => 'Rainfall Data Report',
            'average_daily' => 'Average Rainfall per Day Report',
            'average_monthly' => 'Average Rainfall per Month Report'
        ];
        return $titles[$type] ?? 'Rainfall Report';
    }

    private function getPdfData(string $type, Request $request)
    {
        switch ($type) {
            case 'rainfall':
                return $this->getRainfallDataForPdf($request);
            case 'average_daily':
                return $this->getDailyAverageDataForPdf($request);
            case 'average_monthly':
                return $this->getMonthlyAverageDataForPdf($request);
            default:
                return collect([]);
        }
    }

    private function getRainfallDataForPdf(Request $request)
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('DAY(r.created_at) as day'),
                DB::raw('YEAR(r.created_at) as year'),
                'r.created_at',
                'r.rain_tips as tip_count',
                'r.cumulative_rainfall',
                'r.intensity_level'
            ]);

        $this->applyFilters($query, $request);
        
        return $query->orderBy('r.created_at', 'desc')->get();
    }

    private function getDailyAverageDataForPdf(Request $request)
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('DATE(r.created_at) as date'),
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('DAY(r.created_at) as day'),
                DB::raw('YEAR(r.created_at) as year'),
                DB::raw('ROUND(AVG(r.cumulative_rainfall), 2) as average_rainfall'),
                DB::raw('ROUND(SUM(r.rain_tips), 2) as total_tips'),
                DB::raw('ROUND(SUM(r.cumulative_rainfall), 2) as total_rainfall')
            ]);

        $this->applyFilters($query, $request);
        
        return $query
            ->groupBy('d.dev_id', 'r.dev_location', DB::raw('DATE(r.created_at)'), DB::raw('MONTH(r.created_at)'), DB::raw('DAY(r.created_at)'), DB::raw('YEAR(r.created_at)'))
            ->orderBy(DB::raw('DATE(r.created_at)'), 'desc')
            ->get();
    }

    private function getMonthlyAverageDataForPdf(Request $request)
    {
        $query = DB::table('rainfalls as r')
            ->leftJoin('devices as d', 'd.dev_id', '=', 'r.device_id')
            ->select([
                'd.dev_id',
                'r.dev_location',
                DB::raw('MONTH(r.created_at) as month'),
                DB::raw('YEAR(r.created_at) as year'),
                DB::raw('ROUND(AVG(r.cumulative_rainfall), 2) as average_rainfall'),
                DB::raw('SUM(r.rain_tips) as total_tips'),
                DB::raw('ROUND(SUM(r.cumulative_rainfall), 2) as total_rainfall')
            ]);

        $this->applyFilters($query, $request);
        
        return $query
            ->groupBy('d.dev_id', 'r.dev_location', DB::raw('MONTH(r.created_at)'), DB::raw('YEAR(r.created_at)'))
            ->orderBy(DB::raw('YEAR(r.created_at)'), 'desc')
            ->orderBy(DB::raw('MONTH(r.created_at)'), 'desc')
            ->get();
    }

    private function calculateSummary($data, string $type): array
    {
        if ($data->isEmpty()) {
            return [
                'Total Records' => 0,
                'Total Rainfall' => '0.00 mm',
                'Avg Rainfall' => '0.00 mm'
            ];
        }

        $totalRainfall = $data->sum('cumulative_rainfall') ?? $data->sum('total_rainfall') ?? 0;
        $avgRainfall = $data->avg('cumulative_rainfall') ?? $data->avg('average_rainfall') ?? 0;
        
        return [
            'Total Records' => $data->count(),
            'Total Rainfall' => number_format($totalRainfall, 2) . ' mm',
            'Avg Rainfall' => number_format($avgRainfall, 2) . ' mm'
        ];
    }

    public function getTrendAnalysisData(Request $request)
    {
        try {
            $location = $request->input('location');
            $year = $request->input('year', date('Y'));
            $period = $request->input('period');

            $query = DB::table('rainfalls')
                ->select([
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('ROUND(SUM(cumulative_rainfall), 2) as total_rainfall')
                ])
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'));

            if ($location) {
                $query->where('dev_location', $location);
            }

            if ($period) {
                $months = match($period) {
                    'q1' => [1, 2, 3],
                    'q2' => [4, 5, 6],
                    'q3' => [7, 8, 9],
                    'q4' => [10, 11, 12],
                    default => range(1, 12)
                };
                $query->whereIn(DB::raw('MONTH(created_at)'), $months);
            }

            $currentYearData = $query->pluck('total_rainfall', 'month')->toArray();

            // Get historical average
            $historicalQuery = DB::table('rainfalls')
                ->select([
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('ROUND(AVG(cumulative_rainfall), 2) as avg_rainfall')
                ])
                ->where('created_at', '<', "$year-01-01")
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'));

            if ($location) {
                $historicalQuery->where('dev_location', $location);
            }

            if ($period) {
                $historicalQuery->whereIn(DB::raw('MONTH(created_at)'), $months);
            }

            $historicalData = $historicalQuery->pluck('avg_rainfall', 'month')->toArray();

            // Fill in missing months with 0
            $months = range(1, 12);
            $formattedCurrentYear = array_map(function($month) use ($currentYearData) {
                return $currentYearData[$month] ?? 0;
            }, $months);

            $formattedHistorical = array_map(function($month) use ($historicalData) {
                return $historicalData[$month] ?? 0;
            }, $months);

            return response()->json([
                'success' => true,
                'data' => [
                    'currentYear' => $formattedCurrentYear,
                    'historicalAverage' => $formattedHistorical,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in trend analysis: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving trend data'
            ], 500);
        }
    }
}
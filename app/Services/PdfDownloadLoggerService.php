<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PdfDownloadLoggerService
{
    /**
     * Log PDF download into reports table
     *
     * @param string $reportType The type of report (e.g., 'Device Information', 'Contacts List', 'Message History')
     * @param array $params Optional parameters like start_date, end_date, filters used
     * @return Report|null
     */
    public function logPdfDownload(string $reportType, array $params = []): ?Report
    {
        try {
            $reportData = [
                'user_id' => Auth::id(),
                'requestor_type' => 'no_requestor', // Internal system download
                'report_type' => $reportType,
                'start_date' => $params['start_date'] ?? null,
                'end_date' => $params['end_date'] ?? null,
                'purpose' => $this->generatePurposeText($reportType, $params),
                'status' => 'completed', // PDF was successfully generated and downloaded
                'organization' => 'MDRRMO', // Internal download
            ];

            $report = Report::create($reportData);
            
            Log::info('PDF download logged', [
                'report_id' => $report->report_id,
                'report_type' => $reportType,
                'user_id' => Auth::id(),
                'params' => $params
            ]);

            return $report;
        } catch (\Exception $e) {
            Log::error('Failed to log PDF download', [
                'report_type' => $reportType,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'params' => $params
            ]);
            return null;
        }
    }

    /**
     * Generate descriptive purpose text for the download
     *
     * @param string $reportType
     * @param array $params
     * @return string
     */
    private function generatePurposeText(string $reportType, array $params = []): string
    {
        $purpose = "System generated PDF export of {$reportType}";
        
        // Add date range if provided
        if (!empty($params['start_date']) && !empty($params['end_date'])) {
            $startDate = Carbon::parse($params['start_date'])->format('M j, Y');
            $endDate = Carbon::parse($params['end_date'])->format('M j, Y');
            $purpose .= " from {$startDate} to {$endDate}";
        } elseif (!empty($params['start_date'])) {
            $startDate = Carbon::parse($params['start_date'])->format('M j, Y');
            $purpose .= " from {$startDate}";
        }

        // Add applied filters
        $filters = [];
        if (!empty($params['search'])) {
            $filters[] = "search: '{$params['search']}'";
        }
        if (!empty($params['status'])) {
            $filters[] = "status: '{$params['status']}'";
        }
        if (!empty($params['position'])) {
            $filters[] = "position: '{$params['position']}'";
        }
        if (!empty($params['level'])) {
            $filters[] = "intensity level: '{$params['level']}'";
        }
        if (!empty($params['location'])) {
            $filters[] = "location: '{$params['location']}'";
        }

        if (!empty($filters)) {
            $purpose .= " with filters applied (" . implode(', ', $filters) . ")";
        }

        $purpose .= ". Downloaded on " . Carbon::now()->format('M j, Y \a\t g:i A') . ".";

        return $purpose;
    }

    /**
     * Get report type mappings for different modules
     *
     * @return array
     */
    public static function getReportTypes(): array
    {
        return [
            'devices' => 'Device Management Report',
            'contacts' => 'Contacts Information Report', 
            'messages' => 'Message Management Report',
            'users' => 'User Management Report',
            'rainfall' => 'Rainfall History Report',
            'reports' => 'Reports Summary Report',
        ];
    }
}
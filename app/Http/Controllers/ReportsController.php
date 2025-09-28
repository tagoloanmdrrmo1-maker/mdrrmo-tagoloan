<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Requestor;
use App\Models\Contact;
use App\Models\Device;
use App\Models\User;
use App\Models\Message;
use App\Models\Rainfall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\PdfDownloadLoggerService;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Read reports if the table exists; otherwise, show empty list
        $reports = collect();
        try {
            if (DB::getSchemaBuilder()->hasTable('reports')) {
                $query = Report::with(['user', 'requestor']);
                
                // Search functionality
                if ($request->has('search') && !empty($request->search)) {
                    $searchTerm = $request->search;
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('report_type', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('purpose', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('organization', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('status', 'LIKE', "%{$searchTerm}%")
                          ->orWhereHas('requestor', function ($requestorQuery) use ($searchTerm) {
                              $requestorQuery->where('first_name', 'LIKE', "%{$searchTerm}%")
                                             ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                             ->orWhere('organization', 'LIKE', "%{$searchTerm}%");
                          });
                    });
                }
                
                // Filter by report type
                if ($request->has('report_type') && !empty($request->report_type)) {
                    $query->where('report_type', $request->report_type);
                }
                
                // Filter by status
                if ($request->has('status') && !empty($request->status)) {
                    $query->where('status', $request->status);
                }
                
                $reports = $query->orderBy('created_at', 'desc')
                    ->paginate(15)
                    ->appends($request->query());
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching reports: ' . $e->getMessage());
            $reports = collect();
        }

        // Get all requestors for the modal dropdown
        $requestors = collect();
        try {
            if (DB::getSchemaBuilder()->hasTable('requestors')) {
                $requestors = Requestor::orderBy('first_name')
                    ->orderBy('last_name')
                    ->get();
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching requestors: ' . $e->getMessage());
            $requestors = collect();
        }

        return view('reports.report', [
            'reports' => $reports,
            'requestors' => $requestors,
        ]);
    }

    public function store(Request $request)
    {
        // Basic validation rules based on requestor_type
        $rules = [
            'requestor_type' => 'required|in:no_requestor,old_requestor,new_requestor',
            'report_type' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        // Add requestor-specific fields based on requestor_type
        if ($request->requestor_type === 'old_requestor') {
            $rules['requestor_id'] = 'required|exists:requestors,requestor_id';
            $rules['purpose'] = 'required|string|min:10|max:500';
        } elseif ($request->requestor_type === 'new_requestor') {
            // Fields for new requestor
            $rules['first_name'] = 'required|string|max:255';
            $rules['middle_name'] = 'nullable|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            $rules['organization'] = 'required|string|max:255';
            $rules['purpose'] = 'required|string|min:10|max:500';
        } else {
            // For no_requestor, purpose is optional
            $rules['purpose'] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $reportData = [
                'user_id' => Auth::id(),
                'requestor_type' => $request->requestor_type,
                'report_type' => $request->report_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'purpose' => $request->purpose,
                'status' => 'pending',
            ];

            // Handle requestor based on type
            if ($request->requestor_type === 'old_requestor') {
                $reportData['requestor_id'] = $request->requestor_id;
                
                // Get organization from the existing requestor
                $requestor = Requestor::find($request->requestor_id);
                if ($requestor) {
                    $reportData['organization'] = $requestor->organization;
                }
            } elseif ($request->requestor_type === 'new_requestor') {
                // Create new requestor
                $requestor = Requestor::create([
                    'first_name' => $request->first_name ?? '',
                    'middle_name' => $request->middle_name ?? '',
                    'last_name' => $request->last_name ?? '',
                    'organization' => $request->organization ?? 'N/A',
                ]);
                
                $reportData['requestor_id'] = $requestor->requestor_id;
                $reportData['organization'] = $request->organization;
            } else {
                // For 'no_requestor', set default organization
                $reportData['organization'] = 'MDDRMO';
            }

            $report = Report::create($reportData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Report created successfully.',
                'report' => $report->load(['user', 'requestor'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating report: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create report. Please try again.',
                'errors' => ['general' => ['An error occurred while creating the report.']]
            ], 422);
        }
    }

    public function show($id)
    {
        try {
            $report = Report::with(['user', 'requestor'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,completed,rejected',
        ]);

        try {
            $report = Report::findOrFail($id);
            $report->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report status updated successfully.',
                'report' => $report->load(['user', 'requestor'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating report: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report status.'
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting report: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete report.'
            ], 422);
        }
    }

    public function getRequestors(Request $request)
    {
        try {
            $query = Requestor::query();
            
            // Search functionality for requestors
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('organization', 'LIKE', "%{$searchTerm}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
                });
            }
            
            $requestors = $query->orderBy('first_name')
                ->orderBy('last_name')
                ->paginate(10)
                ->appends($request->query());
            
            return response()->json([
                'success' => true,
                'requestors' => $requestors->items(),
                'pagination' => [
                    'current_page' => $requestors->currentPage(),
                    'last_page' => $requestors->lastPage(),
                    'per_page' => $requestors->perPage(),
                    'total' => $requestors->total(),
                    'from' => $requestors->firstItem(),
                    'to' => $requestors->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching requestors: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load requestors.'
            ], 500);
        }
    }

    /**
     * Generate report with preview data.
     */
    public function generateReport(Request $request)
    {
        try {
            Log::info('Report generation started', ['request_data' => $request->all()]);
            
            // Same validation as store method
            $rules = [
                'requestor_type' => 'required|in:no_requestor,old_requestor,new_requestor',
                'report_type' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ];

            // Add requestor-specific fields based on requestor_type
            if ($request->requestor_type === 'old_requestor') {
                $rules['requestor_id'] = 'required|exists:requestors,requestor_id';
                $rules['purpose'] = 'required|string|min:10|max:500';
            } elseif ($request->requestor_type === 'new_requestor') {
                // Fields for new requestor
                $rules['first_name'] = 'required|string|max:255';
                $rules['middle_name'] = 'nullable|string|max:255';
                $rules['last_name'] = 'required|string|max:255';
                $rules['organization'] = 'required|string|max:255';
                $rules['purpose'] = 'required|string|min:10|max:500';
            } else {
                // For no_requestor, purpose is optional
                $rules['purpose'] = 'nullable|string|max:500';
            }

            Log::info('Validating request data with rules');
            $validatedData = $request->validate($rules);
            Log::info('Validation passed', ['validated_data' => $validatedData]);

            // Get report data based on type and date range
            Log::info('Getting report data for type: ' . $request->report_type);
            $reportData = $this->getReportData($request->report_type, $request->start_date, $request->end_date);
            Log::info('Report data retrieved', ['data_count' => is_countable($reportData) ? count($reportData) : 'unknown']);
            
            // Prepare response with report info and data
            $response = [
                'success' => true,
                'report_info' => [
                    'report_type' => $request->report_type,
                    'requestor_type' => $request->requestor_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'purpose' => $request->purpose ?? "System generated PDF export of {$request->report_type}",
                    'date_range' => $this->formatDateRange($request->start_date, $request->end_date),
                ],
                'data' => $reportData,
                'pdf_url' => route('reports.downloadPdf') . '?' . http_build_query($request->all())
            ];

            // Add requestor info if applicable - DO NOT CREATE DATABASE ENTRIES
            if ($request->requestor_type === 'old_requestor') {
                Log::info('Processing old requestor');
                $requestor = Requestor::find($request->requestor_id);
                if ($requestor) {
                    $response['report_info']['requestor_name'] = trim(($requestor->first_name ?? '') . ' ' . ($requestor->last_name ?? ''));
                    $response['report_info']['organization'] = $requestor->organization;
                } else {
                    // Fallback if requestor not found
                    $response['report_info']['requestor_name'] = 'Unknown Requestor';
                    $response['report_info']['organization'] = 'N/A';
                }
            } elseif ($request->requestor_type === 'new_requestor') {
                Log::info('Processing new requestor');
                // Just use the form data, don't create database entry yet
                $response['report_info']['requestor_name'] = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
                $response['report_info']['organization'] = $request->organization ?? 'N/A';
            } else {
                Log::info('Processing no requestor - setting defaults');
                $response['report_info']['requestor_name'] = 'MDDRMO';
                $response['report_info']['organization'] = 'MDDRMO';
            }

            Log::info('Report generation completed successfully');
            return response()->json($response);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in generateReport', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error generating report', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get report data based on type and date range.
     */
    private function getReportData($reportType, $startDate = null, $endDate = null)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        switch ($reportType) {
            case 'rainfall_history':
                return $this->getRainfallData($start, $end);
            
            case 'message_history':
                return $this->getMessageData($start, $end);
            
            case 'device_info':
                return $this->getDeviceData($start, $end);
            
            case 'contacts_info':
                return $this->getContactsData($start, $end);
            
            case 'user_management':
                return $this->getUserData($start, $end);
            
            case 'reports_summary':
                return $this->getReportsData($start, $end);
            
            default:
                throw new \Exception('Unknown report type: ' . $reportType);
        }
    }

    /**
     * Get rainfall data.
     */
    private function getRainfallData($start, $end)
    {
        return Rainfall::with('device')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($rainfall) {
                return [
                    'date' => $rainfall->created_at->format('M d, Y H:i'),
                    'location' => $rainfall->dev_location ?? 'N/A',
                    'rainfall_mm' => number_format($rainfall->cumulative_rainfall ?? 0, 2),
                    'tips' => $rainfall->rain_tips ?? 0,
                    'intensity' => $rainfall->intensity_level ?? 'N/A',
                ];
            });
    }

    /**
     * Get message data.
     */
    private function getMessageData($start, $end)
    {
        return Message::with(['contact', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($message) {
                return [
                    'date' => $message->created_at->format('M d, Y H:i'),
                    'contact' => $message->contact ? trim(($message->contact->firstname ?? '') . ' ' . ($message->contact->lastname ?? '')) : 'N/A',
                    'location' => $message->brgy_location ?? 'N/A',
                    'intensity' => $message->intensity_level ?? 'N/A',
                    'status' => $message->status ?? 'N/A',
                    'message' => substr($message->text_desc ?? '', 0, 50) . (strlen($message->text_desc ?? '') > 50 ? '...' : ''),
                ];
            });
    }

    /**
     * Get device data.
     */
    private function getDeviceData($start, $end)
    {
        return Device::when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderBy('dev_location')
            ->get()
            ->map(function ($device) {
                return [
                    'device_id' => $device->dev_id ?? 'N/A',
                    'serial_number' => $device->serial_number ?? 'N/A',
                    'location' => $device->dev_location ?? 'N/A',
                    'status' => $device->status ?? 'N/A',
                    'date_installed' => $device->date_installed ? Carbon::parse($device->date_installed)->format('M d, Y') : 'N/A',
                    'coordinates' => ($device->latitude && $device->longitude) 
                        ? $device->latitude . ', ' . $device->longitude 
                        : 'N/A',
                ];
            });
    }

    /**
     * Get contacts data.
     */
    private function getContactsData($start, $end)
    {
        return Contact::when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get()
            ->map(function ($contact) {
                return [
                    'name' => trim(($contact->firstname ?? '') . ' ' . ($contact->lastname ?? '')),
                    'contact_number' => $contact->contact_num ?? 'N/A',
                    'location' => $contact->brgy_location ?? 'N/A',
                    'position' => $contact->position ?? 'N/A',
                    'date_added' => $contact->created_at ? $contact->created_at->format('M d, Y') : 'N/A',
                ];
            });
    }

    /**
     * Get user data.
     */
    private function getUserData($start, $end)
    {
        return User::when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderBy('username')
            ->get()
            ->map(function ($user) {
                return [
                    'username' => $user->username ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'role' => $user->role ?? 'N/A',
                    'full_name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                    'date_created' => $user->created_at ? $user->created_at->format('M d, Y') : 'N/A',
                ];
            });
    }

    /**
     * Get reports data.
     */
    private function getReportsData($start, $end)
    {
        return Report::with(['user', 'requestor'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'report_id' => 'RPT' . str_pad($report->report_id, 4, '0', STR_PAD_LEFT),
                    'report_type' => $report->report_type ? ucwords(str_replace('_', ' ', $report->report_type)) : 'N/A',
                    'requestor' => $report->requestor 
                        ? trim(($report->requestor->first_name ?? '') . ' ' . ($report->requestor->last_name ?? '')) 
                        : ($report->requestor_type === 'no_requestor' ? 'MDDRMO' : 'N/A'),
                    'organization' => $report->organization ?? 'N/A',
                    'status' => $report->status ?? 'N/A',
                    'date_created' => $report->created_at ? $report->created_at->format('M d, Y') : 'N/A',
                ];
            });
    }

    /**
     * Format date range for display.
     */
    private function formatDateRange($startDate, $endDate)
    {
        if (!$startDate && !$endDate) {
            return 'Last 30 days';
        }
        
        $start = $startDate ? Carbon::parse($startDate)->format('M d, Y') : 'Beginning';
        $end = $endDate ? Carbon::parse($endDate)->format('M d, Y') : 'Today';
        
        return $start . ' to ' . $end;
    }

    /**
     * Download PDF for generated report.
     */
    public function downloadPdf(Request $request)
    {
        try {
            Log::info('PDF download request received', ['request_data' => $request->all()]);
            
            // Create requestor and report entries in database when PDF is actually downloaded
            DB::beginTransaction();
            
            $reportData = [];
            
            // Handle requestor creation/lookup only when downloading
            if ($request->requestor_type === 'old_requestor') {
                $requestor = Requestor::find($request->requestor_id);
                if ($requestor) {
                    $reportData['requestor_id'] = $requestor->requestor_id;
                    $reportData['organization'] = $requestor->organization;
                }
            } elseif ($request->requestor_type === 'new_requestor') {
                // Create new requestor only when downloading PDF
                $requestor = Requestor::create([
                    'first_name' => $request->first_name ?? '',
                    'middle_name' => $request->middle_name ?? '',
                    'last_name' => $request->last_name ?? '',
                    'organization' => $request->organization ?? 'N/A',
                ]);
                
                $reportData['requestor_id'] = $requestor->requestor_id;
                $reportData['organization'] = $request->organization;
            } else {
                // For no requestor, set default values
                $reportData['organization'] = 'MDDRMO';
            }
            
            // Create report entry in database
            $reportData = array_merge([
                'user_id' => Auth::id(),
                'requestor_type' => $request->requestor_type,
                'report_type' => $request->report_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'purpose' => $request->purpose,
                'status' => 'completed', // Mark as completed since PDF is being downloaded
            ], $reportData);
            
            $report = Report::create($reportData);
            
            // Get the report data
            Log::info('Getting report data for PDF download');
            $pdfReportData = $this->getReportData($request->report_type, $request->start_date, $request->end_date);
            Log::info('Report data retrieved for PDF', ['data_count' => is_countable($pdfReportData) ? count($pdfReportData) : 'unknown']);
            
            // Prepare data for PDF view
            $pdfData = [
                'report_type' => $request->report_type,
                'report_type_display' => ucwords(str_replace('_', ' ', $request->report_type)),
                'date_range' => $this->formatDateRange($request->start_date, $request->end_date),
                'data' => $pdfReportData,
                'generated_at' => Carbon::now()->format('F j, Y \\a\\t g:i A'),
            ];

            // Add requestor info if applicable
            if ($request->requestor_type === 'old_requestor') {
                $requestor = Requestor::find($request->requestor_id);
                if ($requestor) {
                    $pdfData['requestor_name'] = trim(($requestor->first_name ?? '') . ' ' . ($requestor->last_name ?? ''));
                    $pdfData['organization'] = $requestor->organization;
                } else {
                    // Fallback if requestor not found
                    $pdfData['requestor_name'] = 'Unknown Requestor';
                    $pdfData['organization'] = 'N/A';
                }
            } elseif ($request->requestor_type === 'new_requestor') {
                $pdfData['requestor_name'] = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
                $pdfData['organization'] = $request->organization ?? 'N/A';
            } else {
                $pdfData['requestor_name'] = 'MDDRMO';
                $pdfData['organization'] = 'MDDRMO';
            }

            // Log the PDF download - FIXED: Skip logging if this is from preview modal
            if (!$request->has('preview_modal')) {
                $loggerService = new PdfDownloadLoggerService();
                $reportTypes = PdfDownloadLoggerService::getReportTypes();
                $reportTypeKey = $this->getReportTypeKey($request->report_type);
                $reportTypeName = $reportTypes[$reportTypeKey] ?? 'Unknown Report';
                
                $params = [
                    'report_type' => $request->report_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'requestor_type' => $request->requestor_type,
                ];
                $loggerService->logPdfDownload($reportTypeName, $params);
            }

            // Generate PDF using the appropriate view
            $viewName = $this->getPdfViewName($request->report_type);
            $pdf = app('dompdf.wrapper')->loadView($viewName, $pdfData)
                ->setPaper('a4', $this->getPdfOrientation($request->report_type))
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                ]);

            $filename = $this->generatePdfFilename($request->report_type);
            
            DB::commit();
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get report type key for logger service.
     */
    private function getReportTypeKey($reportType)
    {
        $mapping = [
            'rainfall_history' => 'rainfall',
            'message_history' => 'messages',
            'device_info' => 'devices',
            'contacts_info' => 'contacts',
            'user_management' => 'users',
            'reports_summary' => 'reports',
        ];
        
        return $mapping[$reportType] ?? 'reports';
    }

    /**
     * Get PDF view name based on report type.
     */
    private function getPdfViewName($reportType)
    {
        // For now, use generic template for all report types
        // Later we can create specific templates for each type
        return 'pdf.generic';
        
        /* Future specific templates:
        $mapping = [
            'rainfall_history' => 'pdf.rainfall',
            'message_history' => 'pdf.messages',
            'device_info' => 'pdf.devices',
            'contacts_info' => 'pdf.contacts',
            'user_management' => 'pdf.users',
            'reports_summary' => 'pdf.reports',
        ];
        
        return $mapping[$reportType] ?? 'pdf.generic';
        */
    }

    /**
     * Get PDF orientation based on report type.
     */
    private function getPdfOrientation($reportType)
    {
        $landscapeTypes = ['message_history', 'reports_summary', 'device_info'];
        return in_array($reportType, $landscapeTypes) ? 'landscape' : 'portrait';
    }

    /**
     * Generate PDF filename based on report type.
     */
    private function generatePdfFilename($reportType)
    {
        $typeNames = [
            'rainfall_history' => 'rainfall_history',
            'message_history' => 'message_management',
            'device_info' => 'device_management',
            'contacts_info' => 'contacts_information',
            'user_management' => 'user_management',
            'reports_summary' => 'reports_summary',
        ];
        
        $typeName = $typeNames[$reportType] ?? 'report';
        return $typeName . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
    }

    /**
     * Export reports to PDF.
     */
    public function exportPdf(Request $request)
    {
        try {
            // Read reports if the table exists; otherwise, show empty list
            $reports = collect();
            if (DB::getSchemaBuilder()->hasTable('reports')) {
                $query = Report::with(['user', 'requestor']);
                
                // Search functionality
                if ($request->has('search') && !empty($request->search)) {
                    $searchTerm = $request->search;
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('report_type', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('purpose', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('organization', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('status', 'LIKE', "%{$searchTerm}%")
                          ->orWhereHas('requestor', function ($requestorQuery) use ($searchTerm) {
                              $requestorQuery->where('first_name', 'LIKE', "%{$searchTerm}%")
                                             ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                             ->orWhere('organization', 'LIKE', "%{$searchTerm}%");
                          });
                    });
                }
                
                // Filter by report type
                if ($request->has('report_type') && !empty($request->report_type)) {
                    $query->where('report_type', $request->report_type);
                }
                
                // Filter by status
                if ($request->has('status') && !empty($request->status)) {
                    $query->where('status', $request->status);
                }
                
                $reports = $query->orderBy('created_at', 'desc')->get();
            }

            // Log the PDF download to reports table
            $loggerService = new PdfDownloadLoggerService();
            $reportType = PdfDownloadLoggerService::getReportTypes()['reports'];
            $params = [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
            ];
            $loggerService->logPdfDownload($reportType, $params);

            // Generate PDF
            $pdf = app('dompdf.wrapper')->loadView('pdf.reports', compact('reports'))
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                ]);

            $filename = 'reports_summary_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error generating reports PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

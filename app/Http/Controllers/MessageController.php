<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Device;
use App\Models\Rainfall;

class MessageController extends Controller
{
    const STATUS_SENT = 'Sent';
    const INTENSE_LEVELS = ['intense', 'torrential'];

    // Show alerts page
    public function index(Request $request)
    {
        $data = $this->getLatestDeviceRainfall($request);
        $data['allDevices'] = DB::table('devices')
            ->select('dev_id', 'serial_number', 'dev_location', 'status')
            ->orderBy('dev_location')
            ->get();
            
        return view('message', $data);
    }

    protected function getLatestDeviceRainfall($request = null)
    {
        // Build the base query
        $query = Message::with(['rainfall.device', 'contact', 'user'])
            ->orderBy('mes_id', 'desc');

        // Apply filters if request is provided
        if ($request && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('intensity_level', 'LIKE', "%{$search}%")
                  ->orWhere('brgy_location', 'LIKE', "%{$search}%")
                  ->orWhere('contact_num', 'LIKE', "%{$search}%")
                  ->orWhere('text_desc', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhereHas('contact', function($contactQuery) use ($search) {
                      $contactQuery->where('firstname', 'LIKE', "%{$search}%")
                                   ->orWhere('lastname', 'LIKE', "%{$search}%")
                                   ->orWhere('middlename', 'LIKE', "%{$search}%")
                                   ->orWhere('brgy_location', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request && $request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request && $request->filled('level') && $request->level !== 'all') {
            $query->where('intensity_level', $request->level);
        }

        // Apply pagination
        $messages = $query->paginate(10);

        $contacts = Contact::all();

        $devices = DB::table('devices as d')
            ->leftJoin(DB::raw('(
                SELECT dev_location, intensity_level, created_at
                FROM rainfalls r1
                WHERE created_at = (
                    SELECT MAX(created_at)
                    FROM rainfalls r2
                    WHERE r2.dev_location = r1.dev_location
                )
            ) r'), 'r.dev_location', '=', 'd.dev_location')
            ->select('d.dev_location', 'r.intensity_level as latest_intensity')
            ->whereIn('r.intensity_level', self::INTENSE_LEVELS)
            ->orderBy('d.dev_location')
            ->get();

        return compact('messages', 'contacts', 'devices');
    }

    public function send(Request $request)
    {
        // Check if this is an AJAX request
        $isAjax = $request->ajax() || $request->wantsJson();
        
        // Log the request data for debugging
        Log::info('Alert send request started', ['request_data' => $request->all()]);
        
        try {
            // Custom validation logic
            $validated = $request->validate([
                'send_mode' => 'required|in:all,single',
                'contact_type' => 'required|array',
                'contact_type.*' => 'string',
                'message' => 'required|string|min:10|max:1000',
                'selected_area' => 'required_if:send_mode,single|string',
                'message_type' => 'required|string'
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);
            
            // Get target contacts
            $contacts = $this->getTargetContacts($validated, $request);
            
            Log::info('Target contacts retrieved', ['contacts_count' => $contacts->count()]);
            
            if ($contacts->isEmpty()) {
                $errorMessage = 'No contacts found for the selected criteria.';
                Log::warning('No contacts found', ['criteria' => $validated]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false, 
                        'message' => $errorMessage,
                        'errors' => ['contact_type' => [$errorMessage]]
                    ], 422);
                }
                return redirect()->route('messages.index')->with('error', $errorMessage);
            }

            // Start database transaction for data consistency
            DB::beginTransaction();
            
            try {
                $sentCount = $this->createMessages($contacts, $validated);
                
                // Commit the transaction
                DB::commit();
                
                Log::info('Messages created successfully', [
                    'sent_count' => $sentCount,
                    'contacts_count' => $contacts->count()
                ]);

                $successMessage = $sentCount === 1 
                    ? "Message sent successfully" 
                    : "Messages sent successfully to {$sentCount} contacts";
                
                if ($isAjax) {
                    // Add a small delay to ensure frontend processing
                    usleep(100000); // 0.1 second delay
                    
                    return response()->json([
                        'success' => true, 
                        'message' => $successMessage,
                        'data' => [
                            'sent_count' => $sentCount,
                            'contacts_count' => $contacts->count()
                        ]
                    ], 200);
                }
                
                return redirect()->route('messages.index')->with('success', $successMessage);
                
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors before submitting.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->route('alerts.index')
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Alert sending failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            $errorMessage = 'Failed to send alerts. Please try again.';
            
            if ($isAjax) {
                return response()->json([
                    'success' => false, 
                    'message' => $errorMessage,
                    'error_details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->route('alerts.index')->with('error', $errorMessage);
        }
    }

    private function getTargetContacts(array $data, Request $request)
    {
        Log::info('Getting target contacts', ['data' => $data]);
        
        // Handle 'all' mode
        if ($data['send_mode'] === 'all') {
            $intenseLocations = Rainfall::whereIn('intensity_level', self::INTENSE_LEVELS)
                ->pluck('dev_location')
                ->unique();
                
            $contacts = $intenseLocations->isEmpty() 
                ? Contact::all() 
                : Contact::whereIn('brgy_location', $intenseLocations)->get();
            
            Log::info('All mode contacts retrieved', ['count' => $contacts->count()]);
            return $contacts;
        }

        // Handle 'single' mode
        $selectedArea = $data['selected_area'];
        
        // Check if selected area has intense rainfall
        $hasIntenseRain = Rainfall::where('dev_location', $selectedArea)
            ->whereIn('intensity_level', self::INTENSE_LEVELS)
            ->exists();

        if (!$hasIntenseRain) {
            Log::warning('Selected area does not have intense rainfall', ['area' => $selectedArea]);
            throw new \Exception('Selected area does not have intense/torrential rainfall.');
        }

        // Check if "all" contacts in the area should be selected
        if (in_array('all', $data['contact_type'])) {
            $contacts = Contact::where('brgy_location', $selectedArea)->get();
            Log::info('Single mode - all contacts in area', ['area' => $selectedArea, 'count' => $contacts->count()]);
            return $contacts;
        }

        // Filter specific contact IDs
        $validContactIds = array_filter($data['contact_type'], function($id) {
            return is_numeric($id) && !empty($id);
        });

        Log::info('Processing specific contacts', [
            'contact_type_raw' => $data['contact_type'],
            'valid_contact_ids' => $validContactIds,
            'selected_area' => $selectedArea
        ]);

        if (!empty($validContactIds)) {
            $contacts = Contact::whereIn('contact_id', $validContactIds)
                ->where('brgy_location', $selectedArea)
                ->get();
            
            Log::info('Specific contacts retrieved', ['count' => $contacts->count()]);
            return $contacts;
        }

        Log::warning('No valid contact IDs found');
        return collect();
    }

    private function createMessages($contacts, array $data)
    {
        $sentCount = 0;
        $batchSize = 10; // Process in batches to avoid memory issues
        
        Log::info('Starting message creation', ['total_contacts' => $contacts->count()]);
        
        foreach ($contacts->chunk($batchSize) as $contactBatch) {
            $messagesToInsert = [];
            
            foreach ($contactBatch as $contact) {
                $deviceIntensity = $this->getDeviceIntensity($contact->brgy_location);
                
                $messagesToInsert[] = [
                    'intensity_level' => $deviceIntensity ?: 'moderate',
                    'contact_id' => $contact->contact_id,
                    'brgy_location' => $contact->brgy_location,
                    'contact_num' => $contact->contact_num,
                    'text_desc' => $data['message'],
                    'status' => 'pending',
                    'date_created' => now(),
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $sentCount++;
            }
            
            // Bulk insert for better performance
            if (!empty($messagesToInsert)) {
                Message::insert($messagesToInsert);
            }
        }
        
        Log::info('Message creation completed', ['sent_count' => $sentCount]);
        return $sentCount;
    }

    private function getDeviceIntensity($location)
    {
        return DB::table('devices as d')
            ->leftJoin(DB::raw('(
                SELECT dev_location, intensity_level, created_at
                FROM rainfalls r1
                WHERE created_at = (
                    SELECT MAX(created_at)
                    FROM rainfalls r2
                    WHERE r2.dev_location = r1.dev_location
                )
            ) r'), 'r.dev_location', '=', 'd.dev_location')
            ->where('d.dev_location', $location)
            ->value('r.intensity_level');
    }

    public function fetchPending()
    {
        try {
            // Removed profile eager loading since we're getting user info directly from users table
            $messages = Message::with(['contact', 'user'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json($messages);
        } catch (\Exception $e) {
            Log::error('Failed to fetch pending messages', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch pending messages'], 500);
        }
    }

    public function updateStatus($id, Request $request)
    {
        try {
            $request->validate(['status' => 'required|in:sent,failed,pending']);

            // Convert status to lowercase for consistency
            $status = strtolower($request->status);

            // Removed profile eager loading since we're getting user info directly from users table
            $message = Message::with(['contact', 'user'])->findOrFail($id);
            $message->update(['status' => $status]);

            Log::info('Message status updated', ['message_id' => $id, 'status' => $request->status]);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Failed to update message status', ['message_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            // Build the base query
            $query = Message::with(['contact', 'user', 'rainfall.device'])
                ->orderBy('mes_id', 'desc');

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('intensity_level', 'LIKE', "%{$search}%")
                      ->orWhere('brgy_location', 'LIKE', "%{$search}%")
                      ->orWhere('contact_num', 'LIKE', "%{$search}%")
                      ->orWhere('text_desc', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%")
                      ->orWhereHas('contact', function($contactQuery) use ($search) {
                          $contactQuery->where('firstname', 'LIKE', "%{$search}%")
                                       ->orWhere('lastname', 'LIKE', "%{$search}%")
                                       ->orWhere('middlename', 'LIKE', "%{$search}%")
                                       ->orWhere('brgy_location', 'LIKE', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('level') && $request->level !== 'all') {
                $query->where('intensity_level', $request->level);
            }

            $messages = $query->paginate(10);

            // Log PDF download if service exists
            if (class_exists('PdfDownloadLoggerService')) {
                (new PdfDownloadLoggerService())->logPdfDownload(
                    PdfDownloadLoggerService::getReportTypes()['messages'],
                    $request->only(['search', 'status', 'level'])
                );
            }

            $pdf = app('dompdf.wrapper')->loadView('pdf.messages', compact('messages'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('messages_report_' . now()->format('Y-m-d_H-i-s') . '.pdf');

        } catch (\Exception $e) {
            Log::error('PDF export failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
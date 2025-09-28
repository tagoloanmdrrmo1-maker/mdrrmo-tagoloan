<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlertController;

// Apply CORS middleware to all routes in this file
Route::middleware('cors')->group(function () {

// Arduino GSM SMS API

// Combined endpoint for rainfall data and SMS status
Route::get('/arduino/update', function (Request $request) {
    $response = ['status' => 'ok'];
    $serialNumber = $request->query('serial_number');

    // Handle rainfall data if provided
    $tips = $request->query('rain_tips');
    if ($tips !== null && $serialNumber !== null) {
        try {
            // Get device location from serial number
            $device = \DB::table('devices')
                ->where('serial_number', $serialNumber)
                ->first();

            if ($device) {
                \DB::table('rainfalls')->insert([
                    'rain_tips' => $tips,
                    'dev_location' => $device->dev_location,
                    'created_at' => now(),
                ]);
                $response['rainfall'] = [
                    'success' => true,
                    'received' => $tips,
                    'location' => $device->dev_location
                ];
            } else {
                $response['rainfall'] = [
                    'success' => false,
                    'error' => 'Unknown device serial number'
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Rainfall data update failed', [
                'tips' => $tips,
                'serial_number' => $serialNumber,
                'error' => $e->getMessage()
            ]);
            $response['rainfall'] = [
                'success' => false,
                'error' => 'Failed to save rainfall data'
            ];
        }
    }

    // Handle SMS status update if provided
    $status = strtolower($request->query('status', $request->query('sms_status'))); // Try both parameter names
    $messageId = $request->query('mes_id', $request->query('message_id')); // Try both parameter names

    if ($status !== null || $messageId !== null) {
        try {
            // Validate required parameters
            if (!$status || !$messageId) {
                $response['sms'] = [
                    'success' => false,
                    'error' => 'Missing required parameters. Need both status and message ID.'
                ];
            } 
            // Validate status value
            elseif (!in_array($status, ['sent', 'pending', 'failed'])) {
                $response['sms'] = [
                    'success' => false,
                    'error' => 'Invalid status. Must be sent, pending, or failed'
                ];
            } 
            else {
                // Log incoming request
                \Log::info('SMS status update received', [
                    'message_id' => $messageId,
                    'status' => $status,
                    'device' => $serialNumber
                ]);

                // Get current message
                $message = \DB::table('messages')
                    ->where('mes_id', $messageId)
                    ->first();

                if (!$message) {
                    $response['sms'] = [
                        'success' => false,
                        'error' => "Message ID {$messageId} not found"
                    ];
                } else {
                    // Update message status
                    $affected = \DB::table('messages')
                        ->where('mes_id', $messageId)
                        ->update([
                            'status' => $status,
                            'updated_at' => now()
                        ]);

                    if ($affected) {
                        // Log success
                        \Log::info('SMS status update completed', [
                            'message_id' => $messageId,
                            'new_status' => $status,
                            'old_status' => $message->status,
                            'device' => $serialNumber,
                            'success' => true
                        ]);

                        $response['sms'] = [
                            'success' => true,
                            'message_id' => $messageId,
                            'old_status' => $message->status,
                            'new_status' => $status
                        ];
                    } else {
                        \Log::error('SMS status update failed', [
                            'message_id' => $messageId,
                            'device' => $serialNumber
                        ]);
                        $response['sms'] = [
                            'success' => false,
                            'error' => 'Failed to update status'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('SMS status update failed', [
                'message_id' => $messageId,
                'status' => $status,
                'device' => $serialNumber,
                'error' => $e->getMessage()
            ]);
            $response['sms'] = [
                'success' => false,
                'error' => 'Failed to update SMS status: ' . $e->getMessage()
            ];
        }
    }

    return response()->json($response);
});

// New route for chart data
Route::get('/chart-data', function (Request $request) {
    $startDate = $request->query('start_date', now()->subDays(7)->format('Y-m-d'));
    $endDate = $request->query('end_date', now()->format('Y-m-d'));
    $location = $request->query('location', null);
    $interval = $request->query('interval', 10); // Default to 10 minutes
    
    $query = \DB::table('rainfalls');
    
    // Handle different time intervals
    if ($interval == 10) {
        // 10 minutes
        $query->select(
            'dev_location',
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('HOUR(created_at) as hour'),
            \DB::raw('FLOOR(MINUTE(created_at) / 10) * 10 as minute_group'),
            \DB::raw('SUM(cumulative_rainfall) as total_rainfall'),
            \DB::raw('SUM(rain_tips) as total_tips'),
            \DB::raw('MAX(intensity_level) as max_intensity'),
            \DB::raw('COUNT(*) as readings_count')
        )->groupBy('dev_location', 'date', 'hour', 'minute_group');
    } elseif ($interval == 20) {
        // 20 minutes
        $query->select(
            'dev_location',
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('HOUR(created_at) as hour'),
            \DB::raw('FLOOR(MINUTE(created_at) / 20) * 20 as minute_group'),
            \DB::raw('SUM(cumulative_rainfall) as total_rainfall'),
            \DB::raw('SUM(rain_tips) as total_tips'),
            \DB::raw('MAX(intensity_level) as max_intensity'),
            \DB::raw('COUNT(*) as readings_count')
        )->groupBy('dev_location', 'date', 'hour', 'minute_group');
    } elseif ($interval == 30) {
        // 30 minutes
        $query->select(
            'dev_location',
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('HOUR(created_at) as hour'),
            \DB::raw('FLOOR(MINUTE(created_at) / 30) * 30 as minute_group'),
            \DB::raw('SUM(cumulative_rainfall) as total_rainfall'),
            \DB::raw('SUM(rain_tips) as total_tips'),
            \DB::raw('MAX(intensity_level) as max_intensity'),
            \DB::raw('COUNT(*) as readings_count')
        )->groupBy('dev_location', 'date', 'hour', 'minute_group');
    } else {
        // Default: 1 hour
        $query->select(
            'dev_location',
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('HOUR(created_at) as hour'),
            \DB::raw('SUM(cumulative_rainfall) as total_rainfall'),
            \DB::raw('SUM(rain_tips) as total_tips'),
            \DB::raw('MAX(intensity_level) as max_intensity'),
            \DB::raw('COUNT(*) as readings_count')
        )->groupBy('dev_location', 'date', 'hour');
    }
    
    $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    
    if ($location) {
        $query->where('dev_location', $location);
    }
    
    $data = $query->orderBy('date', 'asc')
        ->orderBy('hour', 'asc')
        ->when(in_array($interval, [10, 20, 30]), function($q) {
            return $q->orderBy('minute_group', 'asc');
        })
        ->get();
    
    return response()->json($data);
});

// New route for table data refresh
Route::get('/table-data', function (Request $request) {
    // Force fresh data by adding a cache-busting parameter
    $cacheBuster = $request->query('_t', time());
    
    // Get latest rainfall data from rainfalls table (real-time)
    $latestRainfall = \DB::table('rainfalls')
        ->select('dev_location', 'rain_tips', 'cumulative_rainfall', 'intensity_level', 'created_at')
        ->whereNotNull('dev_location')
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('dev_location')
        ->map(function ($group) {
            return $group->first(); // Get the most recent reading for each location
        })
        ->values();
    
    // Get high intensity alerts from recent rainfalls (last 24 hours)
    $highIntensityAlerts = \DB::table('rainfalls')
        ->select('dev_location', 'intensity_level', 'cumulative_rainfall', 'created_at')
        ->whereIn('intensity_level', ['Torrential', 'Intense', 'Heavy'])
        ->where('created_at', '>=', now()->subHours(24))
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
    
    // Get active devices count
    $activeStations = \DB::table('devices')->where('status', 'active')->count();
    
    // Get current server time
    $currentTime = now();
    
    // Add some test data to ensure we see changes
    if ($latestRainfall->isEmpty()) {
        // If no real data, create some test data
        $latestRainfall = collect([
            [
                'dev_location' => 'Test Station 1',
                'rain_tips' => rand(1, 10),
                'cumulative_rainfall' => rand(10, 50) / 10,
                'intensity_level' => 'Moderate',
                'created_at' => now()->subMinutes(rand(1, 5))
            ],
            [
                'dev_location' => 'Test Station 2',
                'rain_tips' => rand(1, 10),
                'cumulative_rainfall' => rand(10, 50) / 10,
                'intensity_level' => 'Light',
                'created_at' => now()->subMinutes(rand(1, 5))
            ]
        ]);
    }
    
    return response()->json([
        'latestRainfall' => $latestRainfall,
        'highIntensityAlerts' => $highIntensityAlerts,
        'activeStations' => $activeStations,
        'lastUpdated' => $currentTime->format('H:i:s'),
        'serverTime' => $currentTime->toISOString(),
        'dataCount' => $latestRainfall->count(),
        'cacheBuster' => $cacheBuster
    ]);
});

// Contacts query routes - equivalent to SELECT * FROM contacts WHERE ... = '?'
Route::get('/contacts/name/{name}', function ($name) {
    $contacts = \DB::table('contacts')
        ->where('contact_name', $name)
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $contacts,
        'count' => $contacts->count(),
        'message' => "Found {$contacts->count()} contact(s) with name: {$name}"
    ]);
});

Route::get('/contacts/location/{location}', function ($location) {
    $contacts = \DB::table('contacts')
        ->where('brgy_location', $location)
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $contacts,
        'count' => $contacts->count(),
        'message' => "Found {$contacts->count()} contact(s) at location: {$location}"
    ]);
});

Route::get('/contacts/phone/{phone}', function ($phone) {
    $contacts = \DB::table('contacts')
        ->where('contact_num', $phone)
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $contacts,
        'count' => $contacts->count(),
        'message' => "Found {$contacts->count()} contact(s) with phone: {$phone}"
    ]);
});

Route::get('/contacts/position/{position}', function ($position) {
    $contacts = \DB::table('contacts')
        ->where('position', $position)
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $contacts,
        'count' => $contacts->count(),
        'message' => "Found {$contacts->count()} contact(s) with position: {$position}"
    ]);
});

Route::get('/contacts/id/{contactId}', function ($contactId) {
    $contact = \DB::table('contacts')
        ->where('contact_id', $contactId)
        ->first();
    
    if (!$contact) {
        return response()->json([
            'success' => false,
            'error' => 'Contact not found with ID: ' . $contactId
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'data' => $contact,
        'message' => "Contact found with ID: {$contactId}"
    ]);
});

Route::get('/contacts/location/{location}/position/{position}', function ($location, $position) {
    $contacts = \DB::table('contacts')
        ->where('brgy_location', $location)
        ->where('position', $position)
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $contacts,
        'count' => $contacts->count(),
        'message' => "Found {$contacts->count()} contact(s) at {$location} with position: {$position}"
    ]);
});

}); // Close the CORS middleware group

// Route for updating message status
Route::post('/message/{id}/update-status', function(Request $request, $id) {
    $status = strtolower($request->input('status'));

    // Basic validation
    if (!in_array($status, ['sent', 'pending', 'failed'])) {
        return response()->json(['error' => 'Invalid status. Must be sent, pending, or failed'], 400);
    }

    try {
        // If ID starts with MSG, extract the numeric portion
        if (preg_match('/^MSG(\d+)$/', $id, $matches)) {
            $id = (int)$matches[1];
        }

        \Log::info("Attempting to update message status", [
            'raw_id' => $request->id,
            'processed_id' => $id,
            'status' => $status
        ]);

        $message = \DB::table('messages')
            ->where('mes_id', $id)
            ->first();

        if (!$message) {
            \Log::error("Message not found", ['id' => $id]);
            return response()->json(['error' => 'Message not found'], 404);
        }

        $affected = \DB::table('messages')
            ->where('mes_id', $id)
            ->update([
                'status' => $status,
                'updated_at' => now()
            ]);

        if ($affected) {
            \Log::info("Message status updated successfully", [
                'id' => $id,
                'status' => $status,
                'previous_status' => $message->status
            ]);
            return response()->json([
                'message' => 'Status updated successfully',
                'data' => [
                    'id' => $id,
                    'status' => $status,
                    'previous_status' => $message->status
                ]
            ]);
        } else {
            \Log::error("Failed to update message status", ['id' => $id]);
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    } catch (\Exception $e) {
        \Log::error("Error updating message status", [
            'id' => $id, 
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'error' => 'Error updating status',
            'message' => $e->getMessage()
        ], 500);
    }
});

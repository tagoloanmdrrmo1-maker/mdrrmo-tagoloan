<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\PdfDownloadLoggerService;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildDeviceQuery();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('d.serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('d.dev_location', 'LIKE', "%{$search}%")
                  ->orWhere('d.status', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'offline', 'inactive', 'maintenance', 'pending'])) {
            $query->where('d.status', $request->status);
        }

        $devices = $query->orderBy('d.dev_id')->paginate(10);
        return view('devices', ['report4' => $devices]);
    }

    public function show($id)
    {
        $device = $this->buildDeviceQuery()->where('d.dev_id', $id)->first();
        
        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json($device);
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateDeviceData($request);
            
            DB::table('devices')->insert([
                'serial_number' => $validated['serial_number'],
                'dev_location' => ucwords(strtolower($validated['dev_location'])),
                'date_installed' => now(),
                'status' => 'pending',
                'added_by' => Auth::id(), // Add the authenticated user's ID
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('devices.index')->with('success', 'Device added successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add device: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'dev_location' => 'required|string|min:2|max:255|regex:/^[A-Za-z0-9\s\.,\-\/()]+$/|unique:devices,dev_location,' . $id . ',dev_id',
                'status' => 'required|in:active,inactive,maintenance,offline,pending',
            ]);

            $validated['dev_location'] = ucwords(strtolower($validated['dev_location']));
            $validated['updated_at'] = now();

            $updated = DB::table('devices')->where('dev_id', $id)->update($validated);

            $message = $updated ? 'Device updated successfully' : 'Device not found or no changes made';
            return redirect()->route('devices.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update device: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function checkLocation(Request $request)
    {
        if (!$request->location) {
            return response()->json(['exists' => false]);
        }

        $normalizedLocation = ucwords(strtolower(trim($request->location)));
        
        $exists = DB::table('devices')
            ->where('dev_location', $normalizedLocation)
            ->when($request->exclude_device_id, fn($q) => $q->where('dev_id', '!=', $request->exclude_device_id))
            ->exists();
        
        return response()->json(['exists' => $exists]);
    }

    public function checkSerialNumber(Request $request)
    {
        if (!$request->serial_number) {
            return response()->json(['exists' => false]);
        }

        $normalizedSerial = strtoupper(trim($request->serial_number));
        
        $exists = DB::table('devices')
            ->where('serial_number', $normalizedSerial)
            ->when($request->exclude_device_id, fn($q) => $q->where('dev_id', '!=', $request->exclude_device_id))
            ->exists();
        
        return response()->json(['exists' => $exists]);
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = $this->buildDeviceQuery();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('d.serial_number', 'LIKE', "%{$search}%")
                      ->orWhere('d.dev_location', 'LIKE', "%{$search}%")
                      ->orWhere('d.status', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('status') && in_array($request->status, ['active', 'offline', 'inactive', 'maintenance', 'pending'])) {
                $query->where('d.status', $request->status);
            }

            $devices = $query->orderBy('d.dev_id')->paginate(10);

            // Log PDF download
            (new PdfDownloadLoggerService())->logPdfDownload(
                PdfDownloadLoggerService::getReportTypes()['devices'],
                $request->only(['search', 'status'])
            );

            $pdf = app('dompdf.wrapper')->loadView('pdf.devices', compact('devices'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('devices_report_' . now()->format('Y-m-d_H-i-s') . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function buildDeviceQuery()
    {
        return DB::table('devices as d')
            ->leftJoin(DB::raw('(
                SELECT dev_location,
                       cumulative_rainfall,
                       created_at
                FROM rainfalls r1
                WHERE r1.created_at = (
                    SELECT MAX(r2.created_at)
                    FROM rainfalls r2
                    WHERE r2.dev_location = r1.dev_location
                )
            ) latest_rf'), 'latest_rf.dev_location', '=', 'd.dev_location')
            ->leftJoin(DB::raw('(
                SELECT r1.dev_location,
                       r1.intensity_level,
                       r1.created_at
                FROM rainfalls r1
                WHERE r1.created_at = (
                    SELECT MAX(r2.created_at)
                    FROM rainfalls r2
                    WHERE r2.dev_location = r1.dev_location
                )
            ) latest_r'), 'latest_r.dev_location', '=', 'd.dev_location')
            ->leftJoin('users as u', 'u.user_id', '=', 'd.added_by')
            ->select([
                'd.dev_id',
                'd.serial_number',
                'd.dev_location',
                'd.date_installed',
                'd.latitude',
                'd.longitude',
                'd.status',
                'd.added_by',
                'd.created_at',
                'u.first_name',
                'u.last_name',
                'u.username',
                DB::raw('COALESCE(latest_rf.cumulative_rainfall, 0) AS latest_rainfall'),
                DB::raw('latest_r.intensity_level AS latest_intensity'),
                DB::raw('latest_r.created_at AS last_reading')
            ]);
    }

    private function validateDeviceData(Request $request): array
    {
        $request->merge([
            'serial_number' => strtoupper(trim($request->input('serial_number', ''))),
            'dev_location' => trim($request->input('dev_location', '')),
        ]);

        return $request->validate([
            'serial_number' => 'required|string|min:6|max:50|regex:/^[A-Z0-9_-]+$/|unique:devices,serial_number',
            'dev_location' => 'required|string|min:3|max:255|regex:/^[A-Za-z0-9\s\.,\-\/()]+$/|unique:devices,dev_location',
        ], [
            'dev_location.unique' => 'This location is already assigned to another device.',
            'serial_number.unique' => 'This serial number is already in use.',
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Repositories\ContactRepositoryInterface;
use App\Services\ContactService;
use App\Services\PdfDownloadLoggerService;
use Illuminate\Support\Facades\Schema;

class ContactController extends Controller
{
    protected $contactService;
    protected $loggerService;

    public function __construct(ContactService $contactService, PdfDownloadLoggerService $loggerService)
    {
        $this->contactService = $contactService;
        $this->loggerService = $loggerService;
    }

    /**
     * Display a listing of contacts.
     */
    public function index(Request $request): View
    {
        try {
            if (!Schema::hasTable('contacts')) {
                return view('contacts', [
                    'contacts' => collect([]),
                    'availableLocations' => collect([]),
                    'positions' => ['Captain','Co-Captain','Councilor','Secretary','Treasurer','Kagawad','Chairperson','Member'],
                    'error' => 'The contacts table does not exist. Please run the migrations first.'
                ]);
            }

            $search = $request->input('search');
            $position = $request->input('position');

            $availableLocations = $this->contactService->getActiveDeviceLocations();
            // Use paginated contacts instead of all contacts
            $contacts = $this->contactService->getPaginatedContactsWithFormatting(10, $search, $position);

            // Define available positions for the dropdown
            $positions = ['Captain','Co-Captain','Councilor','Secretary','Treasurer','Kagawad','Chairperson','Member'];

            return view('contacts', compact('contacts', 'availableLocations', 'positions'));
        } catch (\Exception $e) {
            return view('contacts', [
                'contacts' => collect([]),
                'availableLocations' => collect([]),
                'positions' => ['Captain','Co-Captain','Councilor','Secretary','Treasurer','Kagawad','Chairperson','Member'],
                'error' => 'Error accessing contacts table: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        try {
            $result = $this->contactService->createContact($request->all());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($result, $result['success'] ? 200 : 422);
            }

            return redirect()->route('contacts.index')
                ->with($result['success'] ? 'success' : 'error', $result['message']);
        } catch (\Exception $e) {
            $message = 'An unexpected error occurred: ' . $e->getMessage();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return redirect()->route('contacts.index')
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
     * Display the specified contact.
     */
    public function show(string $id): View
    {
        $contact = $this->contactService->findContactById((int)$id);
        return view('contacts.show', compact('contact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, string $id)
    {
        try {
            $result = $this->contactService->updateContact((int)$id, $request->all());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($result, $result['success'] ? 200 : 422);
            }

            return redirect()->route('contacts.index')
                ->with($result['success'] ? 'success' : 'error', $result['message']);
        } catch (\Exception $e) {
            $message = 'Failed to update contact: ' . $e->getMessage();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return redirect()->route('contacts.index')
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
      * Remove the specified contact.
      */
    public function destroy(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $result = $this->contactService->deleteContact((int)$id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return redirect()->route('contacts.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Export contacts to PDF.
     */
    public function exportPdf(Request $request)
    {
        try {
            $filters = [
                'search' => $request->input('search'),
                'position' => $request->input('position'),
            ];

            $result = $this->contactService->exportContactsToPdf($filters, $this->loggerService);

            if (!$result['success']) {
                return response()->json($result, 500);
            }

            $pdf = app('dompdf.wrapper')
                ->loadView('pdf.contacts', ['contacts' => $result['contacts']])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                ]);

            return $pdf->download($result['filename']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contacts by name
     */
    public function getContactsByName(string $name)
    {
        try {
            $contacts = $this->contactService->getAllContactsWithFormatting($name, null);
            return response()->json([
                'success' => true,
                'data' => $contacts,
                'count' => $contacts->count(),
                'message' => "Found {$contacts->count()} contact(s) with name: {$name}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error retrieving contacts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contacts by location
     */
    public function getContactsByLocation(string $location)
    {
        try {
            // Filter contacts by location
            $contacts = $this->contactService->getAllContactsWithFormatting(null, $location);
            
            return response()->json([
                'success' => true,
                'data' => $contacts,
                'count' => $contacts->count(),
                'message' => "Found {$contacts->count()} contact(s) in location: {$location}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error retrieving contacts: ' . $e->getMessage()
            ], 500);
        }
    }
}
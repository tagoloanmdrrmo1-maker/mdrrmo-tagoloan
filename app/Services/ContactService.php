<?php

namespace App\Services;

use App\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Get all contacts with optional search and filtering, formatted for the view
     *
     * @param string|null $search
     * @param string|null $position
     * @return Collection
     */
    public function getAllContactsWithFormatting(?string $search = null, ?string $position = null): Collection
    {
        // Check if the contacts table exists
        if (!Schema::hasTable('contacts')) {
            return collect([]);
        }

        $rawContacts = $this->contactRepository->getAllContacts($search, $position);
        
        $contacts = $rawContacts->map(function ($contact) {
            // Provide aliases expected by the Blade template
            $contact->first_name = $contact->firstname ?? '';
            $contact->middle_name = $contact->middlename ?? '';
            $contact->last_name = $contact->lastname ?? '';
            $contact->location = $contact->brgy_location ?? '';
            // Blade expects JSON string of numbers in contact_numbers
            $contact->contact_numbers = json_encode(array_values(array_filter([ (string)($contact->contact_num ?? '') ])));
            return $contact;
        });

        return $contacts;
    }

    /**
     * Get paginated contacts with optional search and filtering, formatted for the view
     *
     * @param int $perPage
     * @param string|null $search
     * @param string|null $position
     * @return LengthAwarePaginator
     */
    public function getPaginatedContactsWithFormatting(int $perPage = 15, ?string $search = null, ?string $position = null): LengthAwarePaginator
    {
        // Check if the contacts table exists
        if (!Schema::hasTable('contacts')) {
            // Return an empty paginator
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        $paginator = $this->contactRepository->getPaginatedContacts($perPage, $search, $position);
        
        $paginator->getCollection()->transform(function ($contact) {
            // Provide aliases expected by the Blade template
            $contact->first_name = $contact->firstname ?? '';
            $contact->middle_name = $contact->middlename ?? '';
            $contact->last_name = $contact->lastname ?? '';
            $contact->location = $contact->brgy_location ?? '';
            // Blade expects JSON string of numbers in contact_numbers
            $contact->contact_numbers = json_encode(array_values(array_filter([ (string)($contact->contact_num ?? '') ])));
            return $contact;
        });

        return $paginator;
    }

    /**
     * Get active device locations for dropdown
     *
     * @return Collection
     */
    public function getActiveDeviceLocations(): Collection
    {
        return $this->contactRepository->getActiveDeviceLocations();
    }

    /**
     * Create a new contact with validation and sanitization
     *
     * @param array $data
     * @return array
     */
    public function createContact(array $data): array
    {
        try {
            // Check if the contacts table exists
            if (!Schema::hasTable('contacts')) {
                return [
                    'success' => false,
                    'message' => 'The contacts table does not exist. Please run the database migrations first.'
                ];
            }
            
            // Sanitize inputs (trim, strip tags, collapse spaces)
            $sanitized = [
                'first_name'     => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['first_name'] ?? '')))),
                'middle_name'    => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['middle_name'] ?? '')))),
                'last_name'      => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['last_name'] ?? '')))),
                'brgy_location'  => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['brgy_location'] ?? '')))),
                'position'       => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['position'] ?? '')))),
                'contact_numbers'=> (array) ($data['contact_numbers'] ?? []),
            ];
            
            // Normalize casing for names and location
            $sanitized['first_name'] = ucwords(strtolower($sanitized['first_name']));
            if ($sanitized['middle_name'] !== '') {
                $sanitized['middle_name'] = ucwords(strtolower($sanitized['middle_name']));
            }
            $sanitized['last_name'] = ucwords(strtolower($sanitized['last_name']));
            $sanitized['brgy_location'] = ucwords(strtolower($sanitized['brgy_location']));
            $sanitized['position'] = $sanitized['position'] !== '' ? ucwords(strtolower($sanitized['position'])) : null;

            // Clean contact numbers to digits only
            $sanitized['contact_numbers'] = array_values(array_filter(array_map(function ($n) {
                $digits = preg_replace('/\D+/', '', (string) $n);
                return $digits;
            }, $sanitized['contact_numbers'])));

            // Use the first contact number to populate the single contact_num column
            $primaryNumber = (string) collect($sanitized['contact_numbers'])->first();

            // Duplicate checks: same full name OR same contact number
            if ($this->contactRepository->contactExistsByName(
                $sanitized['first_name'],
                $sanitized['middle_name'] ?? '',
                $sanitized['last_name']
            )) {
                return [
                    'success' => false,
                    'message' => 'Duplicate contact detected. A contact with the same name already exists.'
                ];
            }

            if ($this->contactRepository->contactExistsByNumber($primaryNumber)) {
                return [
                    'success' => false,
                    'message' => 'Duplicate contact detected. A contact with the same number already exists.'
                ];
            }

            // Build full contact name
            $contactName = trim(preg_replace('/\s+/', ' ', $sanitized['first_name'].' '.($sanitized['middle_name'] ?? '').' '.$sanitized['last_name']));

            $insertData = [
                'firstname' => $sanitized['first_name'],
                'middlename' => $sanitized['middle_name'] ?? '',
                'lastname' => $sanitized['last_name'],
                'brgy_location' => $sanitized['brgy_location'],
                'contact_num' => is_numeric($primaryNumber) ? (int) $primaryNumber : preg_replace('/\D+/', '', $primaryNumber),
                'position' => $sanitized['position'] ?? null,
            ];

            $contact = $this->contactRepository->createContact($insertData);

            // Create notification for new contact
            try {
                Notification::createNewContactNotification($contactName, $sanitized['brgy_location']);

                Log::info('New contact notification created', [
                    'location' => $sanitized['brgy_location'],
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create new contact notification: ' . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Contact created successfully.',
                'contact' => $contact
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create contact: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing contact with validation and sanitization
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateContact(int $id, array $data): array
    {
        try {
            // Sanitize inputs
            $sanitized = [
                'first_name'     => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['first_name'] ?? '')))),
                'middle_name'    => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['middle_name'] ?? '')))),
                'last_name'      => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['last_name'] ?? '')))),
                'brgy_location'  => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['brgy_location'] ?? '')))),
                'position'       => preg_replace('/\s+/', ' ', trim(strip_tags((string) ($data['position'] ?? '')))),
                'contact_numbers'=> (array) ($data['contact_numbers'] ?? []),
            ];
            
            $sanitized['first_name'] = ucwords(strtolower($sanitized['first_name']));
            if ($sanitized['middle_name'] !== '') {
                $sanitized['middle_name'] = ucwords(strtolower($sanitized['middle_name']));
            }
            $sanitized['last_name'] = ucwords(strtolower($sanitized['last_name']));
            $sanitized['brgy_location'] = ucwords(strtolower($sanitized['brgy_location']));
            $sanitized['position'] = $sanitized['position'] !== '' ? ucwords(strtolower($sanitized['position'])) : null;
            
            $sanitized['contact_numbers'] = array_values(array_filter(array_map(function ($n) {
                $digits = preg_replace('/\D+/', '', (string) $n);
                return $digits;
            }, $sanitized['contact_numbers'])));
            
            $primaryNumber = (string) collect($sanitized['contact_numbers'])->first();

            // Duplicate check excluding current record (same full name or same number)
            if ($this->contactRepository->contactExistsByName(
                $sanitized['first_name'],
                $sanitized['middle_name'] ?? '',
                $sanitized['last_name'],
                $id
            )) {
                return [
                    'success' => false,
                    'message' => 'Duplicate contact detected. A contact with the same name already exists.'
                ];
            }

            if ($this->contactRepository->contactExistsByNumber($primaryNumber, $id)) {
                return [
                    'success' => false,
                    'message' => 'Duplicate contact detected. A contact with the same number already exists.'
                ];
            }

            // Build full contact name
            $contactName = trim(preg_replace('/\s+/', ' ', $sanitized['first_name'].' '.($sanitized['middle_name'] ?? '').' '.$sanitized['last_name']));

            $updateData = [
                'firstname' => $sanitized['first_name'],
                'middlename' => $sanitized['middle_name'] ?? '',
                'lastname' => $sanitized['last_name'],
                'brgy_location' => $sanitized['brgy_location'],
                'contact_num' => is_numeric($primaryNumber) ? (int) $primaryNumber : preg_replace('/\D+/', '', $primaryNumber),
                'position' => $sanitized['position'] ?? null,
            ];

            $updated = $this->contactRepository->updateContact($id, $updateData);

            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Contact updated successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Contact not found or no changes made.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update contact: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a contact
     *
     * @param int $id
     * @return array
     */
    public function deleteContact(int $id): array
    {
        try {
            $deleted = $this->contactRepository->deleteContact($id);

            if ($deleted) {
                return [
                    'success' => true,
                    'message' => 'Contact deleted successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Contact not found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete contact: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Export contacts to PDF
     *
     * @param array $filters
     * @param PdfDownloadLoggerService $loggerService
     * @return array
     */
    public function exportContactsToPdf(array $filters, PdfDownloadLoggerService $loggerService): array
    {
        try {
            // Check if the contacts table exists
            if (!Schema::hasTable('contacts')) {
                return [
                    'success' => false,
                    'message' => 'The contacts table does not exist. Please run the database migrations first.'
                ];
            }
            
            $search = $filters['search'] ?? null;
            $position = $filters['position'] ?? null;
            
            // Get contacts based on filters
            $contacts = $this->getAllContactsWithFormatting($search, $position);

            // Log the PDF download to reports table
            $reportType = PdfDownloadLoggerService::getReportTypes()['contacts'];
            $params = [
                'search' => $search,
                'position' => $position,
            ];
            $loggerService->logPdfDownload($reportType, $params);

            // Return data needed for PDF generation
            return [
                'success' => true,
                'contacts' => $contacts,
                'filename' => 'contacts_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error preparing PDF data: ' . $e->getMessage()
            ];
        }
    }
}
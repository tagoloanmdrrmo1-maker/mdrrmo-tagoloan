<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * Get all contacts with optional search and filtering
     *
     * @param string|null $search
     * @param string|null $position
     * @return Collection
     */
    public function getAllContacts(?string $search = null, ?string $position = null): Collection
    {
        $query = Contact::orderBy('contact_id', 'desc');
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('middlename', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('brgy_location', 'LIKE', "%{$search}%")
                  ->orWhere('contact_num', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply position filter if provided
        if ($position) {
            $query->where('position', $position);
        }
        
        return $query->get();
    }

    /**
     * Get paginated contacts with optional search and filtering
     *
     * @param int $perPage
     * @param string|null $search
     * @param string|null $position
     * @return LengthAwarePaginator
     */
    public function getPaginatedContacts(int $perPage = 15, ?string $search = null, ?string $position = null): LengthAwarePaginator
    {
        $query = Contact::orderBy('contact_id', 'desc');
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('middlename', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('brgy_location', 'LIKE', "%{$search}%")
                  ->orWhere('contact_num', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply position filter if provided
        if ($position) {
            $query->where('position', $position);
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Get active device locations for dropdown
     *
     * @return Collection
     */
    public function getActiveDeviceLocations(): Collection
    {
        // Check if the devices table exists
        if (!Schema::hasTable('devices')) {
            return collect([]);
        }
        
        return DB::table('devices')
            ->select('dev_location')
            ->where('status', 'active')
            ->whereNotNull('dev_location')
            ->where('dev_location', '!=', '')
            ->where('dev_location', '!=', 'Unknown')
            ->distinct()
            ->orderBy('dev_location')
            ->pluck('dev_location');
    }

    /**
     * Create a new contact
     *
     * @param array $data
     * @return Contact
     */
    public function createContact(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     * Update an existing contact
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateContact(int $id, array $data): bool
    {
        $contact = Contact::find($id);
        if ($contact) {
            return $contact->update($data);
        }
        return false;
    }

    /**
     * Delete a contact
     *
     * @param int $id
     * @return bool
     */
    public function deleteContact(int $id): bool
    {
        $contact = Contact::find($id);
        if ($contact) {
            return $contact->delete();
        }
        return false;
    }

    /**
     * Find a contact by ID
     *
     * @param int $id
     * @return Contact|null
     */
    public function findContactById(int $id)
    {
        return Contact::find($id);
    }

    /**
     * Check if a contact with the same name already exists
     *
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     * @param int|null $excludeId
     * @return bool
     */
    public function contactExistsByName(string $firstName, string $middleName, string $lastName, ?int $excludeId = null): bool
    {
        $query = Contact::whereRaw('LOWER(firstname) = ?', [strtolower($firstName)])
            ->whereRaw('LOWER(middlename) = ?', [strtolower($middleName)])
            ->whereRaw('LOWER(lastname) = ?', [strtolower($lastName)]);
            
        if ($excludeId) {
            $query->where('contact_id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Check if a contact with the same number already exists
     *
     * @param string $contactNumber
     * @param int|null $excludeId
     * @return bool
     */
    public function contactExistsByNumber(string $contactNumber, ?int $excludeId = null): bool
    {
        $query = Contact::where('contact_num', is_numeric($contactNumber) ? (int) $contactNumber : preg_replace('/\D+/', '', $contactNumber));
        
        if ($excludeId) {
            $query->where('contact_id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
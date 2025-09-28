<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    /**
     * Get all contacts with optional search and filtering
     *
     * @param string|null $search
     * @param string|null $position
     * @return Collection
     */
    public function getAllContacts(?string $search = null, ?string $position = null): Collection;

    /**
     * Get paginated contacts with optional search and filtering
     *
     * @param int $perPage
     * @param string|null $search
     * @param string|null $position
     * @return LengthAwarePaginator
     */
    public function getPaginatedContacts(int $perPage = 15, ?string $search = null, ?string $position = null): LengthAwarePaginator;

    /**
     * Get active device locations for dropdown
     *
     * @return Collection
     */
    public function getActiveDeviceLocations(): Collection;

    /**
     * Create a new contact
     *
     * @param array $data
     * @return mixed
     */
    public function createContact(array $data);

    /**
     * Update an existing contact
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateContact(int $id, array $data): bool;

    /**
     * Delete a contact
     *
     * @param int $id
     * @return bool
     */
    public function deleteContact(int $id): bool;

    /**
     * Find a contact by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findContactById(int $id);

    /**
     * Check if a contact with the same name already exists
     *
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     * @param int|null $excludeId
     * @return bool
     */
    public function contactExistsByName(string $firstName, string $middleName, string $lastName, ?int $excludeId = null): bool;

    /**
     * Check if a contact with the same number already exists
     *
     * @param string $contactNumber
     * @param int|null $excludeId
     * @return bool
     */
    public function contactExistsByNumber(string $contactNumber, ?int $excludeId = null): bool;
}
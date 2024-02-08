<?php

namespace App\Services\Organization;

use App\Entity\Organization;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

interface OrganizationService
{
    /**
     * @param int $id Die Id der Organisation
     * @return Organization
     */
    public function getOrganizationById(int $id): Organization;

    /**
     * @return array
     */
    public function getAllOrganizations(): array;

    public function getOrganizationsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * @param Request $request
     * @param User|null $user
     * @return int
     */
    public function createOrganization(Request $request, #[CurrentUser] ?User $user): int;

    /**
     * Endpoint zum Ändern des Namens der Organisation
     * @param int $id Die Id der Organisation
     * @param Request $request
     * @return void
     */
    public function editOrganization(int $id, Request $request): void;

    /**
     * @param int $id Die Id der Organisation
     * @return void
     */
    public function deleteOrganization(int $id): void;

    /**
     * Fügt einen Nutzer zu einer Organisation hinzu
     * @param int $organizationId
     * @param int $userId
     * @return void
     */
    public function addUser(int $organizationId, int $userId): void;

    /**
     * Entfernt einen Nutzer aus einer Organisation
     * @param int $organizationId
     * @param int $userId
     * @return void
     */
    public function removeUser(int $organizationId, int $userId): void;
}
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
     * @return Organization Die erstellte Organisation
     */
    public function getOrganizationById(int $id): Organization;

    /**
     * @return array
     */
    public function getAllOrganizations(): array;

    /**
     * Filtert Organisationen für ein Benutzer
     * @param User|null $user Der angemeldete Benutzer
     * @return array Die Liste von Organisationen
     */
    public function getOrganizationsForCurrentUser(?User $user): array;

    /**
     * Erstellt eine Organisation für ein Benutzer
     * @param Request $request Die Informationen der Organisation
     * @param User|null $user Der angemeldete Benutzer
     */
    public function createOrganization(Request $request, #[CurrentUser] ?User $user):Organization;

    /**
     *  Ändert der Name der Organisation
     * @param int $id Die Id der Organisation
     * @param Request $request
     */
    public function editOrganization(int $id, Request $request): void;

    /**
     * Logisch löscht eine Organisation
     * @param int $id Die Id der Organisation
     */
    public function deleteOrganization(int $id): void;

    /**
     * Fügt einen Nutzer zu einer Organisation hinzu
     * @param int $organizationId Die Id der Organisation
     * @param int $userId Die Id des angemeldeten Benutzers
     */
    public function addUserToOrganization(int $organizationId, int $userId): void;

    /**
     * Entfernt einen Nutzer aus einer Organisation
     * @param int $organizationId Die Id der Organisation
     * @param int $userId die Id des angemeldeten Benutzers
     */
    public function removeUserFromOrganization(int $organizationId, int $userId): void;
}
<?php

namespace App\Services\Organization;

use App\Entity\Organization;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param Request $request
     * @return Organization
     */
    public function createOrganization(Request $request): Organization;

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
     * @param Request $request Request mit POST-Parametern organisationId und userId
     * @return void
     */
    public function addUser(Request $request): void;

    /**
     * Entfernt einen Nutzer aus einer Organisation
     * @param Request $request Request mit POST-Parametern organisationId und userId
     * @return void
     */
    public function removeUser(Request $request): void;
}
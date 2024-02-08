<?php

namespace App\Services\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface UserService
{
    /**
     * Gibt einen Nutzer anhand der Id zurück.
     * @param int $id id des Users, z. B. /api/user/12
     * @return User|null
     */
    public function getUserById(int $id): ?User;

    /**
     * Sucht nach einem Nutzer anhand der übergebenen Filter.
     * @param array $criteria Suchfilter, z. B. ['isDeleted' => 'false', 'firstName' => 'Jane']
     * @param array|null $orderBy Sortierparameter, z. B. ['updatedAt' => 'DESC']
     * @return User|null
     */
    public function getUserBy(array $criteria, array $orderBy = null): ?User;

    /**
     * Gibt ein Array mit allen Nutzerobjekten zurück
     * @return array
     */
    public function getAllUsers(): array;

    /**
     * Sucht nach Nutzern anhand der übergebenen Filtern.
     * @param array $criteria Suchfilter, z. B. ['isDeleted' => 'true', 'firstName' => 'Jane']
     * @param array|null $orderBy Sortierparameter, z. B. ['updatedAt' => 'DESC']
     * @param $limit
     * @param $offset
     * @return User[]
     */
    public function getUsersBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * Erstellt einen neuen Nutzer.
     * @param Request $request POST-Request mit Nutzerdaten
     * @return array Array in der Form ['userId' => $user->getId(), 'apiToken' => $apiToken->getToken()]
     */
    public function createNewUser(Request $request): array;

    /**
     * Ändert einen Nutzer anhand der Id.
     * @param int $id Die Id des Nutzers
     * @return void Der geänderte Nutzer aus dem Frontend
     */
    public function editUser(int $id, Request $request): void;

    /**
     * Setzt die Eigenschaft isDeleted bei einem Nutzer auf true.
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void;
}
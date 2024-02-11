<?php

namespace App\Services\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
     * Setzt eine neue E-Mail bei dem eingollgten Nutzer.
     * @param Request $request POST-Request mit ['email' => 'myNewEmail@example.com']
     * @param User|null $user
     * @return void
     */
    public function editUserEmail(Request $request, #[CurrentUser] ?User $user): void;

    /**
     * Setzt ein neues Passwort bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['password' => 'myNewPassword']
     * @param User|null $user
     * @return void
     */
    public function editUserPassword(Request $request, #[CurrentUser] ?User $user): void;

    /**
     * Setzt einen neuen Vornamen bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['firstName' => 'myNewFirstName']
     * @param User|null $user
     * @return void
     */
    public function editUserFirstName(Request $request, #[CurrentUser] ?User $user): void;

    /**
     * Setzt einen neuen Nachnamen bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['lastName' => 'myNewLastName']
     * @param User|null $user
     * @return void
     */
    public function editUserLastName(Request $request, #[CurrentUser] ?User $user): void;

    /**
     * Setzt die Eigenschaft isDeleted bei einem Nutzer auf true.
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void;

    /**
     * Loggt ein Benutzer ein.
     * @param User|null $user Der Benutzer, der sich einloggen möchte
     * @return mixed Objekt aus userId und ApiToken
     */
    public function login(?User $user);
}
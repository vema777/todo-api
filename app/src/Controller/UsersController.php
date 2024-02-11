<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: '/api/users')]
class UsersController extends AbstractController
{
    private readonly UserService $userService;

    public function __construct(
        UserService $userService,
    )
    {
        $this->userService = $userService;
    }

    /**
     * Gibt den gefundenen Nutzer als ein JSON Objekt zurück.
     * @param int $id User Id
     * @return JsonResponse
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        return $this->json($user, 200, [], ['groups' => ['main']]);
    }

    /**
     * Gibt ein JSON-Array mit allen nicht gelöschten Nutzern zurück.
     * @return JsonResponse
     */
    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->getAllUsers();
        return $this->json($allUsers, 200, [], ['groups' => ['main']]);
    }

    /**
     * Gibt User Id und Api Token zurück, wenn richtige E-Mail und Passwort übergeben wurden.
     * @param User|null $user
     * @return JsonResponse
     */
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        $userApiToken = $this->userService->login($user);
        return $this->json($userApiToken, JsonResponse::HTTP_OK);
    }

    /**
     * Erstellt einen neuen Nutzer.
     * @param Request $request POST-Request mit Parametern email, password, firstName, lastName
     * @return JsonResponse
     */
    #[Route('/logup', methods: ['POST'])]
    public function createNewUser(Request $request): JsonResponse
    {
        $UserIdAndToken = $this->userService->createNewUser($request);
        return $this->json($UserIdAndToken, JsonResponse::HTTP_CREATED);
    }

    /**
     * Setzt ein neues Passwort bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['password' => 'myNewPassword']
     * @param User|null $user
     * @return JsonResponse
     */
    #[Route('/changeemail', methods: ['POST'])]
    public function changeUserEmail(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->editUserEmail($request, $user);

        return $this->json(['message' => 'Email erfolgreich geändert'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Setzt ein neues Passwort bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['firstName' => 'myNewFirstName']
     * @param User|null $user
     * @return JsonResponse
     */
    #[Route('/changepassword', methods: ['POST'])]
    public function changeUserPassword(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->editUserPassword($request, $user);

        return $this->json(['message' => 'Passwort erfolgreich geändert'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Setzt einen neuen Vornamen bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['firstName' => 'myNewFirstName']
     * @param User|null $user
     * @return JsonResponse
     */
    #[Route('/changefirstname', methods: ['POST'])]
    public function changeUserFirstName(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->editUserFirstName($request, $user);

        return $this->json(['message' => 'Vorname erfolgreich geändert'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Setzt einen neuen Nachnamen bei dem eingeloggten Nutzer.
     * @param Request $request POST-Request mit ['lastName' => 'myNewLastName']
     * @param User|null $user
     * @return JsonResponse
     */
    #[Route('/changelastname', methods: ['POST'])]
    public function changeUserLastName(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->userService->editUserLastName($request, $user);

        return $this->json(['message' => 'Nachname erfolgreich geändert'], JsonResponse::HTTP_NO_CONTENT);
    }
}
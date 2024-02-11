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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

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
     */
    #[OA\Response(
        response: 200,
        description: 'Ein Benutzer als Json',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        return $this->json($user, 200, [], ['groups' => ['main']]);
    }

    /**
     * Gibt ein JSON-Array mit allen nicht gelöschten Nutzern zurück.
     */
    #[OA\Response(
        response: 200,
        description: 'Ein Liste von Benutzern als Json',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->getAllUsers();
        return $this->json($allUsers, 200, [], ['groups' => ['main']]);
    }

    /**
     * Gibt User Id und Api Token zurück, wenn richtige E-Mail und Passwort übergeben wurden.
     */
    #[OA\Response(
        response: 200,
        description: 'ein Objekt aus userId und ApiToken',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        $userApiToken = $this->userService->login($user);
        return $this->json($userApiToken, JsonResponse::HTTP_OK);
    }

    /**
     * Erstellt einen neuen Nutzer.
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'firstName',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'lastName',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'password',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'email',
                            type: 'string'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: 'Ein Benutzer wurde erstellt',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[Route('/logup', methods: ['POST'])]
    public function createNewUser(Request $request): JsonResponse
    {
        $UserIdAndToken = $this->userService->createNewUser($request);
        return $this->json($UserIdAndToken, JsonResponse::HTTP_CREATED);
    }

    /**
     * Setzt eine neue E-mail bei dem eingeloggten Nutzer.
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'email',
                            type: 'string'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('/changeemail', methods: ['PUT'])]
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
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'password',
                            type: 'string'
                        ),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('/changepassword', methods: ['PUT'])]
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
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'firstName',
                            type: 'string'
                        ),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('/changefirstname', methods: ['PUT'])]
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
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'lastName',
                            type: 'string'
                        ),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'users')]
    #[IsGranted('ROLE_USER')]
    #[Route('/changelastname', methods: ['PUT'])]
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
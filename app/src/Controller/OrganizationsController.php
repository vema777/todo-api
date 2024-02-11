<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Organization\OrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[Route('/api/organizations')]
class OrganizationsController extends AbstractController
{
    private readonly OrganizationService $organizationService;

    public function __construct(
        OrganizationService $organizationService,
    )
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Liefert die Liste von Organisationen, die zu einem Benutzer gehören
     */
    #[OA\Response(
        response: 200,
        description: 'Die Liste von Organisationen',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getOrganizationsForCurrentUser(#[CurrentUser] ?User $user): JsonResponse
    {
        $organizations = $this->organizationService->getOrganizationsForCurrentUser($user);
        return $this->json($organizations);
    }

    /**
     * Erstellt eine Organisation
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: 'Die Organisation wurde erstellt',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createOrganization(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        $organization = $this->organizationService->createOrganization($request, $user);
        return $this->json($organization, JsonResponse::HTTP_CREATED);
    }

    /**
     * Liefert eine Organisation anhand der Id zurück.
     */
    #[OA\Response(
        response: 200,
        description: 'Eine Organisation',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getOrganization(int $id): JsonResponse
    {
        $organization = $this->organizationService->getOrganizationById($id);
        return $this->json($organization);
    }

    /**
     * Logisch löscht eine Organisation(setzt isDeleted auf true)
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function deleteOrganization(int $id): JsonResponse
    {
        $this->organizationService->deleteOrganization($id);
        return $this->json(['message' => 'Organisation wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Ändert eine Organisation
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
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
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function editOrganization(int $id, Request $request): JsonResponse
    {
        $this->organizationService->editOrganization($id, $request);
        return $this->json(['message' => 'Organisation wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Einen Benutzer zur Organisation hinzufügen
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '/{organizationId}/{userId}', methods: ['PUT'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER', 'ROLE_ORGANIZATION_ADMIN')]
    public function addUserToOrganization(int $organizationId, int $userId): JsonResponse
    {
        $this->organizationService->addUserToOrganization($organizationId, $userId);
        return $this->json([
            'message' => 'Nutzer wurde erfolgreich zu der Organisation hinzugefügt'
        ], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Löscht ein Nutzer aus eine Organisation
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'organisationen')]
    #[Route(path: '/{organizationId}/{userId}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER', 'ROLE_ORGANIZATION_ADMIN')]
    public function removeUserFromOrganization(int $organizationId, int $userId): JsonResponse
    {
        $this->organizationService->removeUserFromOrganization($organizationId, $userId);
        return $this->json([
            'message' => 'Nutzer wurde erfolgreich aus der Organisation entfernt'
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}
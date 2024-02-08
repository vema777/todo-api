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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/organizations')]
class OrganizationsController extends AbstractController
{
    private readonly OrganizationService $organizationService;
    private NormalizerInterface $normalizer;

    public function __construct(
        OrganizationService $organizationService,
        NormalizerInterface $normalizer,
    )
    {
        $this->organizationService = $organizationService;
        $this->normalizer = $normalizer;
    }

    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getOrganizationsForCurrentUser(#[CurrentUser] ?User $user): JsonResponse
    {
        $organizations = $this->organizationService->getOrganizationsBy([
            'isDeleted' => false,
            'owner' => $user
        ]);
        return $this->json(
            $this->normalizer->normalize($organizations, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'id',
                    'name',
//                    'owner',
                    'createdAt',
                    'updatedAt',
                ]
            ])
        );
    }

    #[Route(path: '', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createOrganization(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        $organization = $this->organizationService->createOrganization($request, $user);
        return $this->json($organization, JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getOrganization(int $id): JsonResponse
    {
        $organization = $this->organizationService->getOrganizationById($id);
        return $this->json($organization);
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function deleteOrganization(int $id): JsonResponse
    {
        $this->organizationService->deleteOrganization($id);
        return $this->json(['message' => 'Organisation wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function editOrganization(int $id, Request $request): JsonResponse
    {
        $this->organizationService->editOrganization($id, $request);
        return $this->json(['message' => 'Organisation wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{organizationId}/{userId}', methods: ['POST'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER', 'ROLE_ORGANIZATION_ADMIN')]
    public function addUserToOrganization(int $organizationId, int $userId): JsonResponse
    {
        $this->organizationService->addUser($organizationId, $userId);
        return $this->json([
            'message' => 'Nutzer wurde erfolgreich zu der Organisation hinzugefügt'
        ], JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{organizationId}/{userId}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER', 'ROLE_ORGANIZATION_ADMIN')]
    public function removeUserFromOrganization(int $organizationId, int $userId): JsonResponse
    {
        $this->organizationService->removeUser($organizationId, $userId);
        return $this->json([
            'message' => 'Nutzer wurde erfolgreich aus der Organisation entfernt'
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}
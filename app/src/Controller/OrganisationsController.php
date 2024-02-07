<?php

namespace App\Controller;

use App\Services\Organization\OrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/organisations')]
class OrganisationsController extends AbstractController
{
    private readonly OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAllOrganizations(): JsonResponse
    {
        $allOrganizations = $this->organizationService->getAllOrganizations();
        return $this->json($allOrganizations);
    }

    #[Route(path: '', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createOrganization(Request $request): JsonResponse
    {
        $organization = $this->organizationService->createOrganization($request);
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

    //TODO add user to organization, remove user from organisation
}
<?php

namespace App\Services\Organization;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class OrganizationServiceImpl implements OrganizationService
{
    private OrganizationRepository $organizationRepository;
    private EntityManagerInterface $entityManager;
    private readonly UserService $userService;

    public function __construct(
        OrganizationRepository $todoListRepository,
        EntityManagerInterface $entityManager,
        UserService            $userService,
    )
    {
        $this->organizationRepository = $todoListRepository;
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    /**
     * @inheritDoc
     */
    public function getOrganizationById(int $id): Organization
    {
        $organization = $this->organizationRepository->find($id);

        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        return $organization;
    }

    /**
     * @inheritDoc
     */
    public function getAllOrganizations(): array
    {
        return $this->organizationRepository->findBy(['isDeleted' => false]);
    }

    /**
     * @inheritDoc
     */
    public function getOrganizationsForCurrentUser(?User $user): array
    {
        return $this->organizationRepository->findBy([
            'isDeleted' => false,
            'owner' => $user
        ]);
    }

    /**
     * @inheritDoc
     */
    public function createOrganization(Request $request, #[CurrentUser] ?User $user):Organization
    {
        $data = json_decode($request->getContent(), true);

        $organization = new Organization();
        $organization->setName($data['name']);
        $organization->setOwner($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization;
    }

    /**
     * @inheritDoc
     */
    public function editOrganization(int $id, Request $request): void
    {
        $organization = $this->getOrganizationById($id);

        $data = json_decode($request->getContent(), true);
        $organization->setName($data['name']);
        $organization->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function deleteOrganization(int $id): void
    {
        $organization = $this->getOrganizationById($id);
        $organization->setIsDeleted(true);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function addUserToOrganization(int $organizationId, int $userId): void
    {
        $user = $this->userService->getUserById($userId);
        $organization = $this->getOrganizationById($organizationId);
        $organization->addUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function removeUserFromOrganization(int $organizationId, int $userId): void
    {
        $user = $this->userService->getUserById($userId);
        $organization = $this->getOrganizationById($organizationId);
        $organization->removeUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }
}
<?php

namespace App\Services\Organization;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Services\Organization\OrganizationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class OrganizationServiceImpl implements OrganizationService
{
    private OrganizationRepository $organizationRepository;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        OrganizationRepository $todoListRepository,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
    )
    {
        $this->organizationRepository = $todoListRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
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
        $criteria = [
            'isDeleted' => false
        ];

        return $this->organizationRepository->findBy($criteria);
    }

    public function getOrganizationsBy(array $criteria = ['isDeleted' => false], array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->organizationRepository->findBy($criteria);
    }

    /**
     * @inheritDoc
     */
    public function createOrganization(Request $request, #[CurrentUser] ?User $user): int
    {
        $data = json_decode($request->getContent(), true);

        $organization = new Organization();
        $organization->setName($data['name']);
        $organization->setOwner($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization->getId();
    }

    /**
     * @inheritDoc
     */
    public function editOrganization(int $id, Request $request): void
    {
        $organization = $this->organizationRepository->find($id);

        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        $object = json_decode($request->getContent(), true);
        $organization->setName($object['name']);
        $organization->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function deleteOrganization(int $id): void
    {
        $organization = $this->organizationRepository->find($id);

        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        $organization->setIsDeleted(true);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function addUser(int $organizationId, int $userId): void
    {
        $organization = $this->organizationRepository->find($organizationId);
        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $organizationId . " wurde nicht gefunden");
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException("Der Nutzer mit der Id: " .
                $userId . " wurde nicht gefunden");
        }

        $organization->addUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function removeUser(int $organizationId, int $userId): void
    {
        $organization = $this->organizationRepository->find($organizationId);
        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $organizationId . " wurde nicht gefunden");
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException("Der Nutzer mit der Id: " .
                $userId . " wurde nicht gefunden");
        }

        $organization->removeUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }
}
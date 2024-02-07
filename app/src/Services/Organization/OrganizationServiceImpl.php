<?php

namespace App\Services\Organization;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Services\Organization\OrganizationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @inheritDoc
     */
    public function createOrganization(Request $request): Organization
    {
        $data = json_decode($request->getContent(), true);
        // TODO: instead get API Token and find the user from the token
        $owner = $this->userRepository->find($data['ownerId']);

        if (!$owner) {
            throw new NotFoundHttpException("Der User mit der Id: " .
                $data['ownerId'] . " wurde nicht gefunden");
        }

        $organization = new Organization();
        $organization->setName($data['name']);
        $organization->setOwner($owner);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization;
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
    public function addUser(Request $request): void
    {
        $data = json_decode($request->getContent(), true);

        $organization = $this->organizationRepository->find($data['organizationId']);
        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $data['organizationId'] . " wurde nicht gefunden");
        }

        $user = $this->userRepository->find($data['userId']);
        if (!$user) {
            throw new NotFoundHttpException("Der Nutzer mit der Id: " .
                $data['userId'] . " wurde nicht gefunden");
        }

        $organization->addUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function removeUser(Request $request): void
    {
        $data = json_decode($request->getContent(), true);

        $organization = $this->organizationRepository->find($data['organizationId']);
        if (!$organization) {
            throw new NotFoundHttpException("Die Organisation mit der Id: " .
                $data['organizationId'] . " wurde nicht gefunden");
        }

        $user = $this->userRepository->find($data['userId']);
        if (!$user) {
            throw new NotFoundHttpException("Der Nutzer mit der Id: " .
                $data['userId'] . " wurde nicht gefunden");
        }

        $organization->removeUser($user);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }
}
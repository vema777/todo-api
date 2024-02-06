<?php

namespace App\Services\User;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceImpl extends AbstractController implements UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository              $userRepository,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher,
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getUserBy(array $criteria, array $orderBy = null): ?User
    {
        return $this->userRepository->findOneBy($criteria, $orderBy);
    }

    /**
     * @inheritDoc
     */
    public function getAllUsers(): array
    {
        // TODO: change to findBy(['isDeleted' = false])?
        return $this->userRepository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getUsersBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->userRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function createNewUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);

        $this->entityManager->persist($user);

        // TODO: move the creation of the token to a separate controller
        $apiToken = new ApiToken();
        $apiToken->setOwnedBy($user);
        $this->entityManager->persist($apiToken);

        $this->entityManager->flush();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function deleteUser(int $id)
    {
        $user = $this->userRepository->find($id);
        $user->setIsDeleted(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        // TODO: change return $id to something else...
        return $id;
    }
}

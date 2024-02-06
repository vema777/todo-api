<?php

namespace App\Services\User;

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
        $email = $request->get('email');
        $password = $request->get('password');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        $this->entityManager->persist($user);
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

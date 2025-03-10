<?php

namespace App\Services\User;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
        return $this->userRepository->findBy(['isDeleted' => false]);
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
    public function createNewUser(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $this->entityManager->persist($user);

        // each user has one API token
        $apiToken = new ApiToken();
        $apiToken->setOwnedBy($user);
        $this->entityManager->persist($apiToken);

        $this->entityManager->flush();

        // for some reason $user->getValidTokenStrings() returns an empty array in this scope
        // but if you log in with the created user after creation, getValidTokenStrings() works as intended
        return [
            'userId' => $user->getId(),
            'apiToken' => $apiToken->getToken(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function editUserEmail(Request $request, #[CurrentUser] ?User $user): void
    {
        $data = json_decode($request->getContent(), true);

        $user->setEmail($data['email']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function editUserPassword(Request $request, #[CurrentUser] ?User $user): void
    {
        $data = json_decode($request->getContent(), true);

        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function editUserFirstName(Request $request, #[CurrentUser] ?User $user): void
    {
        $data = json_decode($request->getContent(), true);

        $user->setFirstName($data['firstName']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function editUserLastName(Request $request, #[CurrentUser] ?User $user): void
    {
        $data = json_decode($request->getContent(), true);

        $user->setLastName($data['lastName']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function deleteUser(int $id): void
    {
        $user = $this->getUserById($id);
        $user->setIsDeleted(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function login(?User $user)
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return [
            'userId' => $user->getId(),
            'apiToken' => $user->getValidTokenStrings()[0],
        ];
    }
}

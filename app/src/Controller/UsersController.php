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
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route(path: '/api/users')]
class UsersController extends AbstractController
{
    private readonly UserService $userService;
    private NormalizerInterface $normalizer;

    public function __construct(
        UserService         $userService,
        NormalizerInterface $normalizer,
    )
    {
        $this->userService = $userService;
        $this->normalizer = $normalizer;
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        return $this->json(
            $this->normalizer->normalize($user, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'id',
                    'email',
                    'roles',
                    'firstName',
                    'lastName',
                    'createdAt',
                    'updatedAt',
                ]
            ])
        );
    }

    /**
     * Gibt ein Array mit allen Nutzerobjekten zurück
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->getAllUsers();
        return $this->json(
            $this->normalizer->normalize($allUsers, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'id',
                    'email',
                    'roles',
                    'firstName',
                    'lastName',
                    'createdAt',
                    'updatedAt',
                ]
            ])
        );
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'userId' => $user->getId(),
            'apiToken' => $user->getValidTokenStrings()[0]
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/logup', methods: ['POST'])]
    public function createNewUser(Request $request): JsonResponse
    {
        $UserIdAndToken = $this->userService->createNewUser($request);

        return $this->json($UserIdAndToken, JsonResponse::HTTP_CREATED);
    }
}
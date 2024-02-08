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

    public function __construct(
        UserService         $userService,
    )
    {
        $this->userService = $userService;
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(int $id): Response
    {
        $user = $this->userService->getUserById($id);
        return $this->json($user, 200, [], ['groups' => ['main']]);
    }

    /**
     * Gibt ein JSON-Array mit allen Nutzerobjekten zurück
     * @return JsonResponse
     */
    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->getAllUsers();
        return $this->json($allUsers, 200, [], ['groups' => ['main']]);
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'userId' => $user->getId(),
            'apiToken' => $user->getValidTokenStrings()[0]
        ]);
    }

    #[Route('/logup', methods: ['POST'])]
    public function createNewUser(Request $request): Response
    {
        $UserIdAndToken = $this->userService->createNewUser($request);

        return $this->json($UserIdAndToken);
    }
}
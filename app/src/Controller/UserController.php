<?php

namespace App\Controller;

use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserController extends AbstractController
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
    #[Route('/api/users/{id}', methods: ['GET'])]
    public function getUserById(int $id): Response
    {
        // TODO: remove password from return and isDeleted
        $user = $this->userService->getUserById($id);
        return $this->json(
            $this->normalizer->normalize($user, 'json')
        );
    }

    /**
     * Gibt ein Array mit allen Nutzerobjekten zurück
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    #[Route('/api/users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->getAllUsers();
        return $this->json(
            $this->normalizer->normalize($allUsers, 'json')
        );
    }

    #[Route('/api/user', methods: ['POST'])]
    public function createNewUser(Request $request): Response
    {
        $user = $this->userService->createNewUser($request);

        return new Response('Saved new user with data: '
            .$user->getEmail().' '
            .$user->getPassword().' '
            .$user->getFirstName().' '
            .$user->getLastName()
        );
    }
}
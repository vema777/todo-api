<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return $this->json([
                'message' => 'Invalid login request: check that the Content-Type header is "application/json"',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "test_token";

        return new Response(null, 204, [
            'User-Id' => $user->getId()
        ]);

        /*return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $token
        ]);*/
    }

    #[Route('/logout', name: 'api_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached');
    }
}
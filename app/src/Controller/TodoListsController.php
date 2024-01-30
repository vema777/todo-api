<?php

namespace App\Controller;

use App\Service\TodoLists\TodoListsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/lists')]
class TodoListsController extends AbstractController
{
    private readonly TodoListsService $todoListsService;

    public function __construct(TodoListsService $todoListsService)
    {
        $this->todoListsService = $todoListsService;
    }

    #[Route(path: '', methods: ['GET'])]
    public function getAllLists(): JsonResponse
    {
        $todoLists = $this->todoListsService->getAllLists();
        return $this->json($todoLists);
    }

    #[Route(path: '',methods: ['POST'])]
    public function createTodoLists(Request $request): JsonResponse
    {
        $todoList= $this->todoListsService->createTodoList($request);
        return $this->json($todoList, JsonResponse::HTTP_CREATED);
    }
}

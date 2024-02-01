<?php

namespace App\Controller;

use App\Service\TodoLists\TodoListsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

#[Route(path: '/api/lists', name: "Listen")]
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
        $todoListsArr = $this->todoListsService->getAllLists();
        return $this->json($todoListsArr);
    }

    #[Route(path: '', methods: ['POST'])]
    public function createTodoLists(Request $request): JsonResponse
    {
        $todoList = $this->todoListsService->createTodoList($request);
        return $this->json($todoList, JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/{id}', methods: ['GET'])]
    public function getSingleList(int $id)
    {
        $todoList = $this->todoListsService->getSingleTodoList($id);
        return $this->json($todoList);
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    public function deleteList(int $id){
        $this->todoListsService->deleteList($id);
        return $this->json(['message' => 'Liste wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT );
    }
}

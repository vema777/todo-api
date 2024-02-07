<?php

namespace App\Controller;

use App\Services\TodoLists\TodoListsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/lists', name: "Listen")]
class TodoListsController extends AbstractController
{
    private readonly TodoListsService $todoListsService;

    public function __construct(TodoListsService $todoListsService)
    {
        $this->todoListsService = $todoListsService;
    }

    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAllLists(): JsonResponse
    {
        $todoListsArr = $this->todoListsService->getAllLists();
        return $this->json($todoListsArr);
    }

    #[Route(path: '', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createTodoLists(Request $request): JsonResponse
    {
        $todoList = $this->todoListsService->createTodoList($request);
        return $this->json($todoList, JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getSingleList(int $id)
    {
        $todoList = $this->todoListsService->getSingleTodoList($id);
        return $this->json($todoList);
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteList(int $id)
    {
        $this->todoListsService->deleteList($id);
        return $this->json(['message' => 'Liste wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function editList(int $id, Request $request)
    {
        $this->todoListsService->editList($id, $request);
        return $this->json(['message' => 'Liste wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }
}

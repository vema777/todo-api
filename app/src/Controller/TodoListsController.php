<?php

namespace App\Controller;

use App\Services\TodoLists\TodoListsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[Route(path: '/api/lists', name: "Listen")]
class TodoListsController extends AbstractController
{
    private readonly TodoListsService $todoListsService;

    public function __construct(TodoListsService $todoListsService)
    {
        $this->todoListsService = $todoListsService;
    }

    /**
     * Liefert nur eine Liste anhand der Id.
     */
    #[OA\Response(
        response: 200,
        description: 'nur eine Liste',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getTodoListById(int $id)
    {
        $todoList = $this->todoListsService->getTodoListById($id);
        return $this->json($todoList);
    }

    /**
     * Liefert eine Liste von Listen, die zu einem
     * Benutzer gehören.
     */
    #[OA\Response(
        response: 200,
        description: 'Die Liste von Listen',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '/users/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getTodoListsByUserId(int $id)
    {
        $todoList = $this->todoListsService->getTodoListsByUserId($id);
        return $this->json($todoList);
    }

    /**
     * Liefert alle existierenden Listen zurück.
     */
    #[OA\Response(
        response: 200,
        description: 'Eine Liste von Listen'
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getAllTodoLists(): JsonResponse
    {
        $todoListsArr = $this->todoListsService->getAllTodoLists();
        return $this->json($todoListsArr);
    }

    /**
     * Erstellt eine neue Liste
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: 'Die Liste wurde erstellt',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createTodoList(Request $request): JsonResponse
    {
        $todoList = $this->todoListsService->createTodoList($request);
        return $this->json($todoList, JsonResponse::HTTP_CREATED);
    }

    /**
     * Ändert der Name einer Liste
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function editTodoList(int $id, Request $request): JsonResponse
    {
        $this->todoListsService->editList($id, $request);
        return $this->json([
            'message' => 'Liste wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Logisch löscht eine Liste(Setzt die Liste auf true)
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'Lists')]
    #[Route(path: '/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteTodoList(int $id): JsonResponse
    {
        $this->todoListsService->deleteList($id);
        return $this->json(['message' => 'Liste wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }
}

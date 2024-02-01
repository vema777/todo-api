<?php

namespace App\Controller;

use App\Services\Tasks\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/tasks')]
class TasksController extends AbstractController
{

    private readonly TaskService $taskService;

    public function __construct(TaskService $taskService)
    {

        $this->taskService = $taskService;

    }

    #[Route(path: '', methods: ['POST'])]
    public function createNewTask(Request $request): JsonResponse
    {

        $task = $this->taskService->createNewTask($request);
        return $this->json($task, JsonResponse::HTTP_CREATED);

    }

    #[Route(path: '/lists/{listId}', methods: ['GET'])]
    public function findTaskByTodoList(int $listId)
    {
        $tasks = $this->taskService->getTasksByLists($listId);
        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    public function deleteTask(int $id)
    {
        $this->taskService->deleteTask($id);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);

    }

    #[Route(path: '/{id}', methods: ['PUT'])]
    public function editTask(int $id, Request $request)
    {
        $this->taskService->editTask($id, $request);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }
}

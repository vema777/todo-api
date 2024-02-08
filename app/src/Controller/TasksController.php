<?php

namespace App\Controller;

use App\Services\Tasks\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/tasks')]
class TasksController extends AbstractController
{
    private readonly TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Erstellt eine Aufgabe, die einem Nutzer gehört und keiner Organisation
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    public function createNewTask(Request $request): JsonResponse
    {
        $task = $this->taskService->createNewTask($request);
        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * Erstellt eine Aufgabe, die einer Organisation gehört
     * @param Request $request organisationId
     * @return JsonResponse
     */
    #[Route(path: '/organisational', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createNewOrganizationalTask(Request $request): JsonResponse
    {
        $task = $this->taskService->createNewOrganizationalTask($request);
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

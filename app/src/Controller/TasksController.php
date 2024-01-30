<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Services\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/tasks')]
class TasksController extends AbstractController
{

    private readonly TaskService $taskService;

    public function __construcr(TaskService $taskService)
    {

        $this->taskService = $taskService;

    }

    #[Route(path: '', methods: ['POST'])]
    public function createNewTask(Request $request): JsonResponse
    {

        $task = $this->taskService->createNewTask($request);
        return $this->json($task, JsonResponse::HTTP_CREATED);

    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Tasks\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
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
     * @param User|null $user aktuell eingolggter Nutzer
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    //#[IsGranted('ROLE_USER')]
    public function createNewTask(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        $task = $this->taskService->createNewTask($request, $user);
        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * Erstellt eine Aufgabe, die einer Organisation gehört
     * @param Request $request organisationId
     * @return JsonResponse
     */
    #[Route(path: '/organizational', methods: ['POST'])]
    //#[IsGranted('ROLE_USER')]
    public function createNewOrganizationalTask(Request $request): JsonResponse
    {
        $task = $this->taskService->createNewOrganizationalTask($request);
        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/lists/{listId}', methods: ['GET'])]
    public function getTasksByListId(int $listId)
    {
        $tasks = $this->taskService->getTasksByListId($listId);

        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    /**
     * Gibt Aufgaben zurück, die vom Nutzer erstellt wurden und die Aufgaben, die diesem Nutzer zugewiesen sind.
     * @param int $id User Id
     * @return JsonResponse
     */
    #[Route(path: '/users/{id}', methods: ['GET'])]
    public function getTasksByUserId(int $id): JsonResponse
    {
        $tasks = $this->taskService->getTasksByUserId($id);

        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    /**
     * Gibt Aufgaben zurück, die einer ausgewählten Organisation gehören.
     * @param int $id Organization Id
     * @return JsonResponse
     */
    #[Route(path: '/organizations/{id}', methods: ['GET'])]
    public function getTasksByOrganisationId(int $id): JsonResponse
    {
        $tasks = $this->taskService->getTasksByOrganizationId($id);

        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    #[Route(path: '/{id}', methods: ['PUT'])]
    public function editTask(int $id, Request $request)
    {
        $this->taskService->editTask($id, $request);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    public function deleteTask(int $id)
    {
        $this->taskService->deleteTask($id);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Weist einem Nutzer eine Aufgabe zu
     * @param int $id Task Id
     * @param int $userId
     * @return JsonResponse
     */
    #[Route(path: '/{id}/assignees/{userId}', methods: ['POST'])]
    public function addAssignee(int $id, int $userId): JsonResponse
    {
        $this->taskService->addAssignee($id, $userId);
        return $this->json([
            'message' => 'Aufgabe wurde erfolgreich dem Nutzer zugewiesen'
        ], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id Task Id
     * @param int $userId
     * @return JsonResponse
     */
    #[Route(path: '/{id}/assignees/{userId}', methods: ['DELETE'])]
    public function removeAssignee(int $id, int $userId): JsonResponse
    {
        $this->taskService->removeAssignee($id, $userId);
        return $this->json([
            'message' => 'Die Aufgabe ist dem Nutzer nicht mehr zugewiesen'
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}

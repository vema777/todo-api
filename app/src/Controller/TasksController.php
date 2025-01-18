<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Services\Tasks\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;


use OpenApi\Attributes as OA;

#[Route(path: '/api/tasks')]
class TasksController extends AbstractController
{
    private readonly TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


    /**
     * Erstellt eine Aufgabe für einen Benutzer.
     */
    #[Route(path: '', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'title',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'priority',
                            type: 'int'
                        ),
                        new OA\Property(
                            property: 'dateOfExpiry',
                            type: 'datetime'
                        ),
                        new OA\Property(
                            property: 'list',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'int'
                                ),
                                new OA\Property(
                                    property: 'name',
                                    type: 'string'
                                ),
                            ],
                            type: 'object'
                        )
                        ,
                        new OA\Property(
                            property: 'user',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'int'
                                ),
                                new OA\Property(
                                    property: 'firstName',
                                    type: 'string'
                                ),
                                new OA\Property(
                                    property: 'lastName',
                                    type: 'string'
                                ),
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: 'Die Aufgabe wurde erstellt',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[IsGranted('ROLE_USER')]
    public function createNewTask(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        $task = $this->taskService->createNewTask($request, $user);
        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * Erstellt eine Aufgabe, die zu einer Organisation gehört
     */
    #[Route(path: '/organizational', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'title',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'priority',
                            type: 'int'
                        ),
                        new OA\Property(
                            property: 'dateOfExpiry',
                            type: 'datetime'
                        ),
                        new OA\Property(
                            property: 'organisation',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'int'
                                ),
                                new OA\Property(
                                    property: 'name',
                                    type: 'string'
                                )
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: 'Die Aufgabe wurde erstellt',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[IsGranted('ROLE_USER')]
    public function createNewOrganizationalTask(Request $request): JsonResponse
    {
        $task = $this->taskService->createNewOrganizationalTask($request);
        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * Liefert eine Liste von Aufgaben zurück, die zu einer Liste gehören.
     */
    #[Route('/lists/{listId}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Die Liste von Aufgaben',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Parameter(
        name: 'listId',
        description: 'Die Id der Liste',
        in: 'path',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'tasks')]
    #[IsGranted('ROLE_USER')]
    public function getTasksByListId(int $listId)
    {
        $tasks = $this->taskService->getTasksByListId($listId);

        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    /**
     * Liefert eine Liste von Aufgaben zurück, die zu einem Benutzer gehören.
     */
    #[Route(path: '/users/{id}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Die Liste von Aufgaben',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Die Id des Benutzers',
        in: 'path',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'tasks')]
    public function getTasksByUserId(int $id): JsonResponse
    {
        $tasks = $this->taskService->getTasksByUserId($id);
        return $this->json($tasks);
    }

    /**
     * Liefert Aufgaben  zurück, die zu  einer Organisation gehören.
     *
     */
    #[Route(path: '/organizations/{id}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Die Liste von Aufgaben',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Die Id der Organisation',
        in: 'path',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'tasks')]
    #[IsGranted('ROLE_USER')]
    public function getTasksByOrganisationId(int $id): JsonResponse
    {
        $tasks = $this->taskService->getTasksByOrganizationId($id);
        return $this->json($tasks, JsonResponse::HTTP_OK);
    }

    /**
     * Markiert eine Aufgabe als Erledigt oder nicht erledigt
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[Route(path: '/status/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function markTaskAsDoneOrUndone(int $id)
    {
        $this->taskService->markTaskAsDoneOrUndone($id);
        return $this->json(['message' => 'Status wurde erfolgreich geändert'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Ändert eine Aufgabe
     */
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'title',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'description',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'priority',
                            type: 'int'
                        ),
                        new OA\Property(
                            property: 'dateOfExpiry',
                            type: 'datetime'
                        ),
                        new OA\Property(
                            property: 'list',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'int'
                                ),
                                new OA\Property(
                                    property: 'name',
                                    type: 'string'
                                ),
                            ],
                            type: 'object'
                        )
                        ,
                        new OA\Property(
                            property: 'user',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'int'
                                ),
                                new OA\Property(
                                    property: 'firstName',
                                    type: 'string'
                                ),
                                new OA\Property(
                                    property: 'lastName',
                                    type: 'string'
                                ),
                            ],
                            type: 'object'
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
    #[OA\Tag(name: 'tasks')]
    #[Route(path: '/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function editTask(int $id, Request $request)
    {
        $this->taskService->editTask($id, $request);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich bearbeitet'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * logisch löscht eine Aufgabe(Setzt isDeleted auf true)
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[Route(path: '/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteTask(int $id)
    {
        $this->taskService->deleteTask($id);
        return $this->json(['message' => 'Aufgabe wurde erfolgreich gelöscht'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Weist einem Nutzer eine Aufgabe zu
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[Route(path: '/{id}/assignees/{userId}', methods: ['PUT'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function addAssignee(int $id, int $userId): JsonResponse
    {
        $this->taskService->addAssignee($id, $userId);
        return $this->json([
            'message' => 'Aufgabe wurde erfolgreich dem Nutzer zugewiesen'
        ], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Einen Benutzer aus einer Aufgabe entfernen
     */
    #[OA\Response(
        response: 204,
        description: 'No Content',
    )]
    #[OA\Response(
        response: 401,
        description: 'Nicht zugelassen',
    )]
    #[OA\Tag(name: 'tasks')]
    #[Route(path: '/{id}/assignees/{userId}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ORGANIZATION_OWNER')]
    public function removeAssignee(int $id, int $userId): JsonResponse
    {
        $this->taskService->removeAssignee($id, $userId);
        return $this->json([
            'message' => 'Die Aufgabe ist dem Nutzer nicht mehr zugewiesen'
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}

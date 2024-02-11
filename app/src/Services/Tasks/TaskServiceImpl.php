<?php

namespace App\Services\Tasks;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Services\TodoLists\TodoListsService;
use App\Services\User\UserService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Organization\OrganizationService;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TaskServiceImpl implements TaskService
{

    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;
    private readonly OrganizationService $organizationService;
    private readonly TodoListsService $todoListsService;
    private readonly UserService $userService;

    public function __construct(
        TaskRepository         $taskRepository,
        EntityManagerInterface $entityManager,
        OrganizationService    $organizationService,
        TodoListsService       $todoListsService,
        UserService            $userService,
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
        $this->organizationService = $organizationService;
        $this->todoListsService = $todoListsService;
        $this->userService = $userService;
    }

    /**
     * @inheritDoc
     */
    public function createNewTask(Request $request, #[CurrentUser] ?User $user): Task
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $todoList = $this->todoListsService->getTodoListById($data['list']['id']);
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setPriority($data['priority']);
        if (isset($data['dateOfExpiry'])) {
            $task->setDateOfExpiry(new \DateTimeImmutable($data['dateOfExpiry']));
        }
        $task->setIsOrganizational(false);
        $task->setList($todoList);
        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function createNewOrganizationalTask(Request $request): Task
    {
        $data = json_decode($request->getContent(), true);

        $organization = $this->organizationService->getOrganizationById($data['organizationId']);
        $todoList = $this->todoListsService->getTodoListById($data['list']['id']);

        $task = new Task();
        $task->setOrganization($organization);
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setPriority($data['priority']);
        if (isset($data['dateOfExpiry'])) {
            $task->setDateOfExpiry(new \DateTimeImmutable($data['dateOfExpiry']));
        }
        $task->setIsOrganizational(true);
        $task->setList($todoList);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function getTasksByListId(int $listId): array
    {
        return $this->taskRepository->findBy(['list' => $listId, 'isDeleted' => false]);
    }

    /**
     * @inheritDoc
     */
    public function getTasksByUserId(int $userId): array
    {
        return $this->taskRepository->findTasksByUserId($userId);
    }

    /**
     * @inheritDoc
     */
    public function getTasksByOrganizationId(int $organizationId): array
    {
        return $this->taskRepository->findBy(['organization' => $organizationId, 'isDeleted' => false]);
    }

    /**
     * @inheritDoc
     */
    public function editTask(int $id, Request $request): void
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new NotFoundHttpException("Die Aufgabe mit der Id: " .
            $id . "wurde nicht gefunden");
        }

        $data = json_decode($request->getContent(), true);

        $list = new TodoList();
        $list->setName($data['list']['name']);
        $list->setId($data['list']['id']);
        $exisitngList = $this->entityManager->find(get_class($list), $list->getId());
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setPriority($data['priority']);
        $task->setUpdatedAt(new DateTime());

        $task->setList($exisitngList);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function deleteTask(int $id): void
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw new NotFoundHttpException("Die Aufgabe mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        $task->setIsDeleted(true);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function addAssignee(int $taskId, int $userId): void
    {
        $user = $this->userService->getUserById($userId);
        $task = $this->taskRepository->find($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Die Aufgabe mit der Id: " .
                $taskId . " wurde nicht gefunden");
        }

        $task->addAssignee($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function removeAssignee(int $taskId, int $userId): void
    {
        $user = $this->userService->getUserById($userId);
        $task = $this->taskRepository->find($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Die Aufgabe mit der Id: " .
                $taskId . " wurde nicht gefunden");
        }

        $task->removeAssignee($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}

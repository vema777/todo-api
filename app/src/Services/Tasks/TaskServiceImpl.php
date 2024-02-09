<?php

namespace App\Services\Tasks;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Services\TodoLists\TodoListsService;
use App\Services\User\UserService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Organization\OrganizationService;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TaskServiceImpl implements TaskService
{

    private TaskRepository $taskRepository;

    private UserService $userService;

    private TodoListsService $todoListsService;

    private EntityManagerInterface $entityManager;
    private readonly OrganizationService $organizationService;

    public function __construct(
        TaskRepository         $taskRepository,
        EntityManagerInterface $entityManager,
        UserService            $userService,
        TodoListsService       $todoListsService,
        OrganizationService    $organizationService,
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->todoListsService = $todoListsService;
        $this->organizationService = $organizationService;
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
        $object = json_decode($request->getContent(), true);


        $existingUser = $this->userService->getUserById($object['user']['id']);
        $exisitngList = $this->todoListsService->getTodoListById($object['list']['id']);

        $data = json_decode($request->getContent(), true);

        $organization = $this->organizationService->getOrganizationById($data['organizationId']);
        $todoList = $this->todoListsService->getTodoListById($data['list']['id']);

        $task = new Task();
        $task->setTitle($object['title']);
        $task->setUser($existingUser);
        $task->setDescription($object['description']);
        $task->setPriority($object['priority']);
        $task->setList($exisitngList);
        if (isset($object['dateOfExpiry'])) {
            $task->setDateOfExpiry(new DateTime($object['dateOfExpiry']));
        }

        $task->setOrganization($organization);
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setPriority($data['priority']);

        if (isset($data['dateOfExpiry'])) {
            $task->setDateOfExpiry(new DateTimeImmutable($data['dateOfExpiry']));
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
        $exisitngList = $this->todoListsService->getTodoListById($data['list']['id']);

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

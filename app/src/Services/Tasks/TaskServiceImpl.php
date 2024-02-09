<?php

namespace App\Services\Tasks;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Repository\OrganizationRepository;
use App\Repository\TaskRepository;
use App\Services\Organization\OrganizationServiceImpl;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Organization\OrganizationService;

class TaskServiceImpl implements TaskService
{

    private TaskRepository $taskRepository;

    private EntityManagerInterface $entityManager;
    private readonly OrganizationService $organizationService;

    public function __construct(
        TaskRepository         $taskRepository,
        EntityManagerInterface $entityManager,
        OrganizationService $organizationService,
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
        $this->organizationService = $organizationService;
    }

    /**
     * @inheritDoc
     */
    public function createNewTask(Request $request): Task
    {
        $object = json_decode($request->getContent(), true);
        $task = new Task();
        $list = new TodoList();
        $list->setName($object['list']['name']);
        $list->setId($object['list']['id']);
        $exisitngList = $this->entityManager->find(get_class($list), $list->getId());
        $task->setTitle($object['title']);
        $task->setDescription($object['description']);
        $task->setPriority($object['priority']);
        if (isset($object['dateOfExpiry'])) {
            $task->setDateOfExpiry(new DateTime($object['dateOfExpiry']));
        }
        $task->setList($exisitngList);


        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function createNewOrganizationalTask(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $organization = $this->organizationService->getOrganizationById($data['organisationId']);

        $task = new Task();
        $task->setOrganization($organization); // TODO
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setPriority($data['priority']);
        if (isset($data['dateOfExpiry'])) {
            $task->setDateOfExpiry(new DateTime($data['dateOfExpiry']));
        }

    }

    /**
     * @inheritDoc
     */
    public function getTasksByLists(int $listId): array
    {
        return $this->taskRepository->findTaskByTodoList($listId);
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
    public function editTask(int $id, Request $request): void
    {
        $task = $this->taskRepository->find($id);

        if (!$task){
            throw new NotFoundHttpException("Die Aufgabe mit der Id: " .
            "wurde nicht gefunden");
        }

        $object = json_decode($request->getContent(), true);
        $list = new TodoList();
        $list->setName($object['list']['name']);
        $list->setId($object['list']['id']);
        $exisitngList = $this->entityManager->find(get_class($list), $list->getId());
        $task->setTitle($object['title']);
        $task->setDescription($object['description']);
        $task->setPriority($object['priority']);
        $task->setUpdatedAt(new DateTime());

        $task->setList($exisitngList);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getTasksByUserId(int $userId): array
    {
        return $this->taskRepository->findTaskByUserId($userId);
    }
}
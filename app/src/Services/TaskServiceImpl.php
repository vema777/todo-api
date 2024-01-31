<?php

namespace App\Services;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskServiceImpl implements TaskService
{

    private TaskRepository $taskRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        TaskRepository         $taskRepository,
        EntityManagerInterface $entityManager
    )
    {

        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;

    }


    /** Erstellt eine Aufgabe anhand eines Jsons
     * @param Request $request
     * @return Task
     */
    public function createNewTask(Request $request): Task
    {
        $object = json_decode($request->getContent(), true);
        $task = new Task();
        $task->setTitle($object['title']);
        $task->setDescription($object['description']);
        $task->setPriority($object['priority']);
        $task->setIsDeleted($object['deleted']);
        $task->setDateOfExpiry(new \DateTime($object['dateOfExpiry']));
        $task->setIsDone($object['done']);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }
}
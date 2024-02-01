<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Repository\TaskRepository;
use Cassandra\Date;
use DateTime;
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
        $list = new TodoList();
        $list->setName($object['list']['name']);
        $list->setId($object['list']['id']);
        $exisitngList = $this->entityManager->find(get_class($list), $list->getId());
        $task->setTitle($object['title']);
        $task->setDescription($object['description']);
        $task->setPriority($object['priority']);
        $task->setIsDeleted($object['deleted']);
        if(isset($object['dateOfExpiry'])){
            $task->setDateOfExpiry(new DateTime($object['dateOfExpiry']));
        }
        $task->setList($exisitngList);
        $task->setIsDone($object['done']);


        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }
}
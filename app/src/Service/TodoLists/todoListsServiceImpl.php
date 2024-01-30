<?php

namespace App\Service\TodoLists;

use App\Entity\TodoList;
use App\Repository\TodoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class todoListsServiceImpl implements TodoListsService
{
    private TodoListRepository $todoListRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TodoListRepository $todoListRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->todoListRepository = $todoListRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllLists(): array
    {
        return $this->todoListRepository->findAll();
    }

    public function createTodoList(Request $request): TodoList
    {
        $object = json_decode($request->getContent(), true);
        $todoList = new TodoList();
        $todoList->setName($object['name']);
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
        return $todoList;
    }
}
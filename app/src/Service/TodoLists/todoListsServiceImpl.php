<?php

namespace App\Service\TodoLists;

use App\Entity\TodoList;
use App\Repository\TodoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class todoListsServiceImpl implements TodoListsService
{
    private TodoListRepository $todoListRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TodoListRepository     $todoListRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->todoListRepository = $todoListRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function getAllLists(): array
    {
        return $this->todoListRepository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function createTodoList(Request $request): TodoList
    {
        $object = json_decode($request->getContent(), true);
        $todoList = new TodoList();
        $todoList->setName($object['name']);
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
        return $todoList;
    }

    /**
     * @inheritDoc
     */
    public function getSingleTodoList(int $id): TodoList
    {
        $todoList = $this->todoListRepository->find($id);

        if (!$todoList) {
            throw new NotFoundHttpException("Die Liste mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        return $todoList;
    }

    /**
     * @inheritDoc
     */
    public function deleteList(int $id): void
    {
        $todoList = $this->todoListRepository->find($id);

        if (!$todoList) {
            throw new NotFoundHttpException("Die Liste mit der Id: " .
                $id . " wurde nicht gefunden");
        }

        $this->entityManager->remove($todoList);
        $this->entityManager->flush();

    }
}
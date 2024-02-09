<?php

namespace App\Services\TodoLists;

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
    public function getTodoListById(int $id): TodoList
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
    public function getAllTodoLists(): array
    {
        return $this->todoListRepository->findBy(['isDeleted' => false]);
    }

    /**
     * @inheritDoc
     */
    public function getTodoListsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->todoListRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function createTodoList(Request $request): TodoList
    {
        $data = json_decode($request->getContent(), true);

        $todoList = new TodoList();
        $todoList->setName($data['name']);

        $this->entityManager->persist($todoList);
        $this->entityManager->flush();

        return $todoList;
    }

    /**
     * @inheritDoc
     */
    public function editList(int $id, Request $request): void
    {
        $data = json_decode($request->getContent(), true);

        $todoList = $this->getTodoListById($id);
        $todoList->setName($data['name']);

        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function deleteList(int $id): void
    {
        $todoList = $this->getTodoListById($id);

        $todoList->setIsDeleted(true);

        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getTodoListsByUserId(int $id): array
    {
        return $this->todoListRepository->findBy(['user' => $id, 'isDeleted' => false]);
    }
}
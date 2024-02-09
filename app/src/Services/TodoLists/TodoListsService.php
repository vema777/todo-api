<?php

namespace App\Services\TodoLists;

use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TodoListsService
{
    /**
     * Holt eine einzelne Liste aus der Datenbank ab.
     * @param int $id  Die Id der Liste
     * @return TodoList Die gefundene Liste.
     */
    public function getTodoListById(int $id): TodoList;

    /**
     * Holt die Liste von Aufgaben-liste aus der Datenbank ab.
     * @return array Eine Liste von TodoListen.
     */
    public function getAllTodoLists(): array;

    /**
     * Holt die Liste von Aufgaben-liste aus der Datenbank anhand der
     * Benutzer-Id ab.
     *
     * @param int $id Die Id des Benutzers.
     * @return array Die Liste von Aufgaben-liste.
     */
    public function getTodoListsByUserId(int $id): array;

    /**
     * Sucht nach Listen anhand der übergebenen Filtern.
     * @param array $criteria Suchfilter, z. B. ['isDeleted' => 'true']
     * @param array|null $orderBy Sortierparameter, z. B. ['updatedAt' => 'DESC']
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getTodoListsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * Erstellt eine neue Liste in die Datenbank.
     * @param Request $request Die Liste die man erstellen möchte.
     * @return TodoList Die erstellte Liste
     */
    public function createTodoList(Request $request): TodoList;

    /**
     * Ändert eine Liste anhand der Id.
     * @param int $id Die Id der Liste
     * @param Request $request Die geänderte Liste aus dem Frontend
     */
    public function editList(int $id, Request $request): void;

    /**
     * Setzt die Eigenschaft isDeleted bei einer Liste auf true.
     * @param int $id die Id der Liste
     */
    public function deleteList(int $id): void;
}

<?php

namespace App\Service\TodoLists;

use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TodoListsService
{
    /**
     * Holt die Liste von Aufgaben-liste aus der Datenbank ab.
     * @return array Eine Liste von TodoListen.
     */
    public function getAllLists(): array;

    /**
     * Erstellt eine neue Liste in die Datenbank.
     * @param Request $request Die Liste die man erstellen möchte.
     * @return TodoList Die erstellte Liste
     */
    public function createTodoList(Request $request): TodoList;

    /**
     * Holt eine einzelne Liste aus der Datenbank ab.
     * @param int $id  Die Id der Liste
     * @return TodoList Die gefundene Liste.
     */
    public function getSingleTodoList(int $id): TodoList;

    /**
     * Löscht eine Liste basiert auf die Id
     * @param int $id die Id der Liste
     */
    public function deleteList(int $id): void;

    /**
     * Ändert eine Liste anhand der Id.
     *
     * @param int $id Die Id der Liste
     * @param Request $request Die geänderte Liste aus dem Frontend
     */
    public function editList(int $id, Request $request): void;
}
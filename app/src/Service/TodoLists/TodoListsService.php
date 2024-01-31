<?php

namespace App\Service\TodoLists;

use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TodoListsService
{
    /**
     * Holt die Liste von Aufgaben-liste aus der Datenbank ab.
     * @return array
     */
    public function getAllLists(): array;

    /**
     * Erstellt eine neue Liste in die Datenbank.
     * @param Request $request Die Liste die man erstellen möchte.
     * @return TodoList
     */
    public function createTodoList(Request $request): TodoList;
}
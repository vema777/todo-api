<?php

namespace App\Service\TodoLists;

use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TodoListsService
{
    public function getAllLists(): array;

    public function createTodoList(Request $request): TodoList;
}
<?php

namespace App\Services\Tasks;


use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TaskService
{

    /**
     * Mehtode um eine Aufgabe zu erstellen
     * @param Request $reques
     */
    public function createNewTask(Request $request);

    /** Methode um alle Aufgaben von einer TodoListe zu hohlen.
     * @param int $listId
     * @return array
     */
    public function getTasksByLists(int $listId): array;


}
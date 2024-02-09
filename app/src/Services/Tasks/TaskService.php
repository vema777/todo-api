<?php

namespace App\Services\Tasks;

use App\Entity\TodoList;
use Symfony\Component\HttpFoundation\Request;

interface TaskService
{
    /**
     * Mehtode um eine Aufgabe zu erstellen
     * @param Request $request Die Aufgabe die man erstellen möchte
     */
    public function createNewTask(Request $request);

    /** Methode um alle Aufgaben von einer TodoListe zu holen.
     * @param int $listId Die Id einer TodoListe
     * @return array Ein Array von Aufgaben
     */
    public function getTasksByLists(int $listId): array;

    /**
     * Methode um Aufgaben anhand der Benutzer-Id abzuholen.
     * @param int $userId Die Id des Benutzers.
     * @return array Die Liste von Aufgaben.
     */
    public function getTasksByUserId(int $userId): array;

    /** Löscht eine Aufgabe anhand der Id.
     * @param int $id Id einer Aufgabe.
     */
    public function deleteTask(int $id): void;

    /**
     * @param int $id Die Id einer Aufgabe
     * @param Request $request Die Aufgabe die man bearbeiten möchte.
     */
    public function editTask(int $id, Request $request): void;
}
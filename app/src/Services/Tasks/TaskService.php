<?php

namespace App\Services\Tasks;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

interface TaskService
{
    /**
     * Methode um eine Aufgabe zu erstellen
     * @param Request $request Die Aufgabe die man erstellen möchte
     * @param ?User $user der Nutzer, der die Aufgabe erstellt hat
     */
    public function createNewTask(Request $request);

    /**
     * Erstellt eine Aufgabe, die einer Organisation gehört
     * @param Request $request
     * @return Task
     */
    public function createNewOrganizationalTask(Request $request): Task;

    /** Methode um alle Aufgaben von einer TodoListe zu holen.
     * @param int $listId Die Id einer TodoListe
     * @return array Ein Array von Aufgaben
     */
    public function getTasksByListId(int $listId): array;

    /**
     * Gibt Aufgaben zurück, die vom Nutzer erstellt wurden und die Aufgaben, die diesem Nutzer zugewiesen sind.
     * @param int $userId Die Id des Benutzers.
     * @return array Die Liste von Aufgaben.
     */
    public function getTasksByUserId(int $userId): array;

    /**
     * Markiert eine Aufgabe als erledigt
     * @param int $id Die Id der Aufgabe
     * @return void
     */
    public function markTaskAsDoneOrUndone(int $id): void;

    /**
     * Gibt Aufgaben zurück, die einer ausgewählten Organisation gehören.
     * @param int $organizationId
     * @return array
     */
    public function getTasksByOrganizationId(int $organizationId): array;

    /** Löscht eine Aufgabe anhand der Id.
     * @param int $id Id einer Aufgabe.
     */
    public function deleteTask(int $id): void;

    /**
     * @param int $id Die Id einer Aufgabe
     * @param Request $request Die Aufgabe die man bearbeiten möchte.
     */
    public function editTask(int $id, Request $request): void;

    /**
     * Weist einem Nutzer eine Aufgabe zu
     * @param int $taskId
     * @param int $userId
     * @return void
     */
    public function addAssignee(int $taskId, int $userId): void;

    /**
     * Entfernt einen Nutzer aus der Liste der Nutzer, denen eine Aufgabe zugewiesen wurde
     * @param int $taskId
     * @param int $userId
     * @return void
     */
    public function removeAssignee(int $taskId, int $userId): void;
}
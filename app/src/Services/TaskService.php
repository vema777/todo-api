<?php

namespace App\Services;


use Symfony\Component\HttpFoundation\Request;

interface TaskService
{

    /**
     * Mehtode um eine Aufgabe zu erstellen
     * @param Request $reques
     */
    public function createNewTask(Request $request);

}
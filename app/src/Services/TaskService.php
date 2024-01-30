<?php

namespace App\Services;


use Symfony\Component\HttpFoundation\Request;

interface TaskService
{
    public function createNewTask(Request $request);

}
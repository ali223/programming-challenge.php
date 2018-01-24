<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Task;

class TasksController extends Controller
{
    public function show($id)
    {
        return new TaskResource(Task::find($id));
    }
}

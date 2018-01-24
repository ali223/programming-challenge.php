<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        return new TaskCollection(
            TaskResource::collection(Task::all())
        );
    }

    public function show($id)
    {
        return new TaskResource(Task::find($id));
    }
}

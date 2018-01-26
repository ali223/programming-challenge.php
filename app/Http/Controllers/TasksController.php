<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Task;
use Illuminate\Http\Request;

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
        return new TaskResource(Task::findOrFail($id));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'data.type' => 'required|in:tasks',
            'data.attributes.title' => 'required',
            'data.attributes.completed' => 'required|boolean'
        ]);

        $task = Task::create([
            'title' => $request->input('data.attributes.title'),
            'completed' => $request->input('data.attributes.completed')
        ]);

        return new TaskResource($task);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'data.type' => 'required|in:tasks',
            'data.id' => 'required|in:' . $id,
            'data.attributes.completed' => 'boolean'
        ]);

        $task = Task::findOrFail($id);

        $task->title = $request->input('data.attributes.title', $task->title);
        $task->completed = $request->input('data.attributes.completed', $task->completed);

        $task->save();

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        $task->delete();
    }
}

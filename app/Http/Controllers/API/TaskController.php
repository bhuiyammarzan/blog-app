<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        dd("hit index");
        return response()->json([
            'tasks' => $request->user()->tasks()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = $request->user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Task created',
            'task' => $task,
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== $request->user()->id, 403);

        $task->update($request->only('title', 'description', 'completed'));

        return response()->json([
            'message' => 'Task updated',
            'task' => $task,
        ]);
    }

    public function destroy(Request $request, Task $task)
    {
        abort_if($task->user_id !== $request->user()->id, 403);

        $task->delete();

        return response()->json([
            'message' => 'Task deleted',
        ]);
    }
}

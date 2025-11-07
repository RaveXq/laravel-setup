<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, Project $project): JsonResponse
    {
        $query = $project->tasks()->with(['author', 'assignee', 'comments']);


        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        $tasks = $query->get();

        return response()->json([
            'project' => $project->only(['id', 'name']),
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task = $project->tasks()->create([
            ...$validated,
            'author_id' => auth()->id(),
        ]);

        $task->load(['author', 'assignee', 'project']);

        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        $user = auth()->user();
        if (!$task->project->isMember($user)) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $task->load(['author', 'assignee', 'project', 'comments.author']);

        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $user = auth()->user();

        if ($task->author_id !== $user->id && $task->project->owner_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);
        $task->load(['author', 'assignee', 'project']);

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $user = auth()->user();

        if ($task->author_id !== $user->id && $task->project->owner_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}

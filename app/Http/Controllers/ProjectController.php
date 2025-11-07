<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $projects = Project::where('owner_id', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['owner', 'users'])
            ->get();

        return response()->json([
            'projects' => $projects,
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $project = Project::create([
            'name' => $validated['name'],
            'owner_id' => $user->id,
        ]);

        $project->users()->attach($user->id, ['role' => 'owner']);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project->load(['owner', 'users']),
        ], 201);
    }

    public function show(Request $request, Project $project): JsonResponse
    {
        return response()->json([
            'project' => $project->load(['owner', 'users', 'tasks']),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        if (!$this->userIsOwner($request->user(), $project)) {
            return response()->json([
                'message' => 'Access denied. Only the project owner can update this project.',
            ], 403);
        }

        $validated = $request->validated();

        $project->update($validated);

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project->load(['owner', 'users']),
        ]);
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        if (!$this->userIsOwner($request->user(), $project)) {
            return response()->json([
                'message' => 'Access denied. Only the project owner can delete this project.',
            ], 403);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully',
        ], 200);
    }

    private function userIsOwner($user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }
}

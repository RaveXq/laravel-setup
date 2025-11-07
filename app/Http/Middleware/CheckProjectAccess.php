<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $project = $request->route('project');

        if (!$project instanceof Project) {
            return $next($request);
        }

        $hasAccess = $project->owner_id === $user->id
            || $project->users()->where('user_id', $user->id)->exists();

        if (!$hasAccess) {
            return response()->json([
                'message' => 'Access denied. You are not a member of this project.',
            ], 403);
        }

        return $next($request);
    }
}

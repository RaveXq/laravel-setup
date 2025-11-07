<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Task $task): JsonResponse
    {
        $user = auth()->user();
        if (!$task->project->isMember($user)) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $comments = $task->comments()->with('author')->get();

        return response()->json([
            'task' => $task->only(['id', 'title']),
            'comments' => $comments,
        ]);
    }

    public function store(Request $request, Task $task): JsonResponse
    {
        $user = auth()->user();
        if (!$task->project->isMember($user)) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $task->comments()->create([
            'body' => $validated['body'],
            'author_id' => $user->id,
        ]);

        $comment->load('author');

        return response()->json($comment, 201);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $user = auth()->user();

        if ($comment->author_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}

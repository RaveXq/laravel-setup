<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);

    Route::middleware('project.access')->group(function () {
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
        Route::match(['put', 'patch'], '/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

        Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
        Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/{task}', [TaskController::class, 'show']);
        Route::match(['put', 'patch'], '/{task}', [TaskController::class, 'update']);
        Route::delete('/{task}', [TaskController::class, 'destroy']);

        Route::get('/{task}/comments', [\App\Http\Controllers\CommentController::class, 'index']);
        Route::post('/{task}/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    });

    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy']);
});
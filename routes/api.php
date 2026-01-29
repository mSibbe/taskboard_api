<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('tasks', TaskController::class)
        ->middleware('task.owner')
        ->middleware('task.overdue.auth')
        ->except(['index', 'store']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/users/{user}/tasks', [TaskController::class, 'userTasks']);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'projectTasks']);
    Route::get('/overdue', [TaskController::class, 'overdue']);
    Route::get('/notifications', function (Request $request) {
        return $request->user()->notifications;
    });
});

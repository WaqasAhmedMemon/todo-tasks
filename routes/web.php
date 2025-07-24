<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::post('/tasks/sort', [TaskController::class, 'sort']);

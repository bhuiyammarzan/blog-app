<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('posts', \App\Http\Controllers\API\PostController::class)->except(['index', 'show']);
});

Route::get('/posts', [\App\Http\Controllers\API\PostController::class, 'index']);
Route::get('/posts/{post}', [\App\Http\Controllers\API\PostController::class, 'show']);

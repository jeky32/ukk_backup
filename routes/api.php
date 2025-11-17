<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;

// Test route
Route::get('/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'Laravel API is working!',
        'database' => config('database.connections.mysql.database'),
        'laravel_version' => app()->version(),
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/dashboard', [ProjectController::class, 'dashboard']);

    // Projects CRUD
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']); // CREATE
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']); // DELETE

    Route::get('/projects/{project}/board', [ProjectController::class, 'getBoard']);
    Route::post('/cards/{card}/status', [ProjectController::class, 'changeStatus']);
    Route::post('/cards/{card}/comment', [ProjectController::class, 'addComment']);
});

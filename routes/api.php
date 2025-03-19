<?php 

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Todo Routes
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::get('/todos/{id}', [TodoController::class, 'show']);
    Route::put('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
});

// Test Route (Tanpa Middleware)
Route::get('/test', function () {
    return response()->json(['message' => 'API Working']);
});

// Test Alert (Dengan Middleware)
Route::get('/todos/nearing-deadline', [TodoController::class, 'nearingDeadline'])
    ->middleware('auth:sanctum');

// Searching & Sorting
Route::get('/todos/search-sort', [TodoController::class, 'searchAndSortTodos'])
    ->middleware('auth:sanctum');

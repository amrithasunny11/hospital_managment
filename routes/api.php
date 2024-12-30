<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\GroupController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    // POST /groups - Create a new group
    Route::post('/groups', [GroupController::class, 'store']);

    // GET /groups - Get all groups (hierarchical structure)
    Route::get('/groups', [GroupController::class, 'index']);

    // GET /groups/{id} - Get a specific group by ID
    Route::get('/groups/{id}', [GroupController::class, 'show']);

    // PUT /groups/{id} - Update a group
    Route::put('/groups/{id}', [GroupController::class, 'update']);

    // DELETE /groups/{id} - Delete a group
    Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
});

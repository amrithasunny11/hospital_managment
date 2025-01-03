<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HospitalController;

use App\Http\Middleware\CheckUserRole;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {

    // GET /groups - Get all groups (hierarchical structure)
    Route::get('/groups', [GroupController::class, 'index']);

    // GET /groups/{id} - Get a specific group by ID
    Route::get('/groups/{id}', [GroupController::class, 'show']);

    // GET /hospitals - Get all hospitals
    Route::get('/hospitals', [HospitalController::class, 'index']);
    // GET /hospitals/{id} - Get a specific hospital by ID
    Route::get('/hospitals/{id}', [HospitalController::class, 'show']);




    // Routes restricted to admin role
    Route::middleware([CheckUserRole::class . ':admin'])->group(function () {
        // POST /hospitals - Create a new hospital
        Route::post('/hospitals', [HospitalController::class, 'store']);
        // PUT /hospitals/{id} - Update a hospital
        Route::put('/hospitals/{id}', [HospitalController::class, 'update']);
        // DELETE /hospitals/{id} - Delete a hospital
        Route::delete('/hospitals/{id}', [HospitalController::class, 'destroy']);


        // POST /groups - Create a new group
        Route::post('/groups', [GroupController::class, 'store']);
        // PUT /groups/{id} - Update a group
        Route::put('/groups/{id}', [GroupController::class, 'update']);
        // DELETE /groups/{id} - Delete a group
        Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
    });
});

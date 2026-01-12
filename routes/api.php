<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;

// ----------------------
// AUTH
// ----------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('api.permission:view-users');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('api.permission:create-users');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('api.permission:edit-users');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.permission:delete-users');
    // API Roles
    Route::get('/roles/{role}', [RoleController::class, 'show'])
        ->middleware('api.permission:view-roles');

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('api.permission:view-roles');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('api.permission:create-roles');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('api.permission:edit-roles');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('api.permission:delete-roles');
    // API Permissions
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])
        ->middleware('api.permission:view-permissions');

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('api.permission:view-permissions');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->middleware('api.permission:create-permissions');

    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])
        ->middleware('api.permission:edit-permissions');

    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
        ->middleware('api.permission:delete-permissions');
});

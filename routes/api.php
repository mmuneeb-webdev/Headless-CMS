<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ContentController;


/*
|--------------------------------------------------------------------------
| Public Content API (Read-Only for Published Content)
|--------------------------------------------------------------------------
*/

// List all content types
Route::get('/content-types', [ContentController::class, 'types']);

// Get specific content type details
Route::get('/content-types/{typeName}', [ContentController::class, 'typeShow']);

// List entries for a content type (only published)
Route::get('/content/{typeName}', [ContentController::class, 'index']);

// Get single entry by slug or ID (only published)
Route::get('/content/{typeName}/{slugOrId}', [ContentController::class, 'show']);

// ----------------------
// AUTH
// ----------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('api.permission:view-users');
    Route::post('/users', [UserController::class, 'store'])
        ->middleware('api.permission:create-users');
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('api.permission:view-users');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('api.permission:edit-users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.permission:delete-users');

    // ----------------------
    // Roles (Read-Only)
    // ----------------------

    Route::get('/roles/{role}', [RoleController::class, 'show'])
        ->middleware('api.permission:view-roles');
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('api.permission:view-roles');

    // Mutations return 403
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

    // ----------------------
    // Permissions (Read-Only)
    // ----------------------

    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])
        ->middleware('api.permission:view-permissions');
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('api.permission:view-permissions');

    // Mutations return 403
    Route::post('/permissions', [PermissionController::class, 'store']);
    Route::put('/permissions/{permission}', [PermissionController::class, 'update']);
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy']);

    // ----------------------
    // Content Management (Full CRUD with Permissions)
    // ----------------------

    // Create content
    Route::post('/content/{typeName}', [ContentController::class, 'store'])
        ->middleware('api.permission:create-content');

    // Update content
    Route::put('/content/{typeName}/{slugOrId}', [ContentController::class, 'update'])
        ->middleware('api.permission:edit-content');

    // Delete content
    Route::delete('/content/{typeName}/{slugOrId}', [ContentController::class, 'destroy'])
        ->middleware('api.permission:delete-content');

    // Publish content
    Route::post('/content/{typeName}/{slugOrId}/publish', [ContentController::class, 'publish'])
        ->middleware('api.permission:publish-content');

    // Unpublish content
    Route::post('/content/{typeName}/{slugOrId}/unpublish', [ContentController::class, 'unpublish'])
        ->middleware('api.permission:unpublish-content');
});

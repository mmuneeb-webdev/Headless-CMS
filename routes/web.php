<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication (Fortify Views)
|--------------------------------------------------------------------------
*/

// Login view
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Password reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Home (After Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/home', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes (Spatie Role Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Redirect /admin → dashboard
        Route::get('/', fn() => redirect()->route('admin.dashboard'));

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Roles (FULL CRUD)
        Route::resource('roles', RoleController::class);

        // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        // route for assigning roles
        Route::post('/users/{user}/roles', [UserController::class, 'updateRole'])
            ->name('users.roles.update');

        // Permissions (FULL CRUD)
        Route::resource('permissions', PermissionController::class);
    });

<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ContentTypeController;
use App\Http\Controllers\Admin\ContentEntryController;
use App\Http\Controllers\Admin\MediaController;

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

// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Password reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

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

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Redirect /admin → dashboard
        Route::get('/', fn() => redirect()->route('admin.dashboard'));

        // Dashboard
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        /*
         * Users
         */
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Route for assigning roles to users
        Route::post('/users/{user}/roles', [UserController::class, 'updateRole'])->name('users.roles.update');

        /*
         * Roles (FULL CRUD)
         */
        Route::resource('roles', RoleController::class);

        /*
         * Permissions (FULL CRUD)
         */
        Route::resource('permissions', PermissionController::class);

        /*
        |--------------------------------------------------------------------------
        | Content Types Management
        |--------------------------------------------------------------------------
        */
        Route::resource('content-types', ContentTypeController::class);

        // Field management for content types
        Route::post('/content-types/{contentType}/fields', [ContentTypeController::class, 'addField'])
            ->name('content-types.fields.store');

        Route::put('/content-types/{contentType}/fields/{field}', [ContentTypeController::class, 'updateField'])
            ->name('content-types.fields.update');

        Route::delete('/content-types/{contentType}/fields/{field}', [ContentTypeController::class, 'deleteField'])
            ->name('content-types.fields.destroy');

        /*
        |--------------------------------------------------------------------------
        | Content Entries Management
        |--------------------------------------------------------------------------
        */
        Route::get('/content/{contentType}/entries', [ContentEntryController::class, 'index'])
            ->name('content-entries.index');

        Route::get('/content/{contentType}/entries/create', [ContentEntryController::class, 'create'])
            ->name('content-entries.create');

        Route::post('/content/{contentType}/entries', [ContentEntryController::class, 'store'])
            ->name('content-entries.store');

        Route::get('/content/{contentType}/entries/{entry}', [ContentEntryController::class, 'show'])
            ->name('content-entries.show');

        Route::get('/content/{contentType}/entries/{entry}/edit', [ContentEntryController::class, 'edit'])
            ->name('content-entries.edit');

        Route::put('/content/{contentType}/entries/{entry}', [ContentEntryController::class, 'update'])
            ->name('content-entries.update');

        Route::delete('/content/{contentType}/entries/{entry}', [ContentEntryController::class, 'destroy'])
            ->name('content-entries.destroy');

        // Publish/Unpublish actions
        Route::post('/content/{contentType}/entries/{entry}/publish', [ContentEntryController::class, 'publish'])
            ->name('content-entries.publish');

        Route::post('/content/{contentType}/entries/{entry}/unpublish', [ContentEntryController::class, 'unpublish'])
            ->name('content-entries.unpublish');

        // Version rollback
        Route::post('/content/{contentType}/entries/{entry}/rollback/{version}', [ContentEntryController::class, 'rollback'])
            ->name('content-entries.rollback');

        /*
        |--------------------------------------------------------------------------
        | Media Management
        |--------------------------------------------------------------------------
        */
        Route::resource('media', MediaController::class);
        Route::get('/media-picker', [MediaController::class, 'picker'])
            ->name('media.picker');
    });

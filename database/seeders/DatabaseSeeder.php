<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS (WEB ONLY)
        |--------------------------------------------------------------------------
        */
        $permissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',
            'view-content-types', 'create-content-types', 'edit-content-types', 'delete-content-types',
            'view-content', 'create-content', 'edit-content', 'delete-content',
            'publish-content', 'unpublish-content',
            'view-media', 'upload-media', 'delete-media',
            'api-access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLES (WEB ONLY — IMPORTANT)
        |--------------------------------------------------------------------------
        */
        $roles = ['super-admin', 'admin', 'editor', 'author', 'viewer'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // Super Admin → all permissions
        Role::where('name', 'super-admin')->first()
            ->givePermissionTo(Permission::all());

        // Admin
        Role::where('name', 'admin')->first()->syncPermissions([
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',
            'view-content-types', 'create-content-types', 'edit-content-types', 'delete-content-types',
            'view-content', 'create-content', 'edit-content', 'delete-content',
            'publish-content', 'unpublish-content',
            'view-media', 'upload-media', 'delete-media',
            'api-access',
        ]);

        // Editor
        Role::where('name', 'editor')->first()->syncPermissions([
            'view-content', 'create-content', 'edit-content', 'delete-content',
            'publish-content', 'unpublish-content',
            'view-media', 'upload-media', 'delete-media',
            'api-access',
        ]);

        // Author
        Role::where('name', 'author')->first()->syncPermissions([
            'view-content', 'create-content', 'edit-content',
            'view-media', 'upload-media',
            'api-access',
        ]);

        // Viewer
        Role::where('name', 'viewer')->first()->syncPermissions([
            'view-content', 'view-media', 'api-access',
        ]);

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        $users = [
            'super-admin' => 'superadmin@contentra.test',
            'admin'       => 'admin@contentra.test',
            'editor'      => 'editor@contentra.test',
            'author'      => 'author@contentra.test',
            'viewer'      => 'viewer@contentra.test',
        ];

        foreach ($users as $role => $email) {
            $user = User::create([
                'name' => ucfirst($role),
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($role); // ONLY ONCE
        }
        $this->command->info('✅ Roles, Permissions, and Test Users (WEB + API) seeded successfully.');
        $this->command->info('');
        $this->command->info('Test Credentials:');
        $this->command->info('Super Admin: superadmin@contentra.test / password');
        $this->command->info('Admin: admin@contentra.test / password');
        $this->command->info('Editor: editor@contentra.test / password');
        $this->command->info('Author: author@contentra.test / password');
        $this->command->info('Viewer: viewer@contentra.test / password');
        $this->call(ContentSeeder::class);
    }
}
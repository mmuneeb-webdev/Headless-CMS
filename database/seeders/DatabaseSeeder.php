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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear cached permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS (WEB + API)
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

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLES (WEB + API)
        |--------------------------------------------------------------------------
        */
        $roles = ['super-admin', 'admin', 'editor', 'author', 'viewer'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);
        }

        // Super Admin → ALL permissions
        Role::where('name', 'super-admin')->where('guard_name', 'web')->first()
            ->givePermissionTo(Permission::where('guard_name', 'web')->get());

        Role::where('name', 'super-admin')->where('guard_name', 'api')->first()
            ->givePermissionTo(Permission::where('guard_name', 'api')->get());

        // Admin permissions
        $adminPermissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'view-permissions', "create-permissions", "delete-permissions", 'edit-permissions',
            'view-content-types', 'create-content-types', 'edit-content-types', 'delete-content-types',
            'view-content', 'create-content', 'edit-content', 'delete-content',
            'publish-content', 'unpublish-content',
            'view-media', 'upload-media', 'delete-media',
        ];

        Role::where('name', 'admin')->where('guard_name', 'web')->first()
            ->syncPermissions($adminPermissions);

        Role::where('name', 'admin')->where('guard_name', 'api')->first()
            ->syncPermissions(array_merge($adminPermissions, ['api-access']));

        // Editor
        $editorPermissions = [
            'view-content', 'create-content', 'edit-content', 'delete-content',
            'publish-content', 'unpublish-content',
            'view-media', 'upload-media', 'delete-media',
        ];

        Role::where('name', 'editor')->where('guard_name', 'web')->first()
            ->syncPermissions($editorPermissions);

        Role::where('name', 'editor')->where('guard_name', 'api')->first()
            ->syncPermissions(array_merge($editorPermissions, ['api-access']));

        // Author
        $authorPermissions = [
            'view-content', 'create-content', 'edit-content',
            'view-media', 'upload-media',
        ];

        Role::where('name', 'author')->where('guard_name', 'web')->first()
            ->syncPermissions($authorPermissions);

        Role::where('name', 'author')->where('guard_name', 'api')->first()
            ->syncPermissions(array_merge($authorPermissions, ['api-access']));

        // Viewer
        $viewerPermissions = ['view-content', 'view-media'];

        Role::where('name', 'viewer')->where('guard_name', 'web')->first()
            ->syncPermissions($viewerPermissions);

        Role::where('name', 'viewer')->where('guard_name', 'api')->first()
            ->syncPermissions(array_merge($viewerPermissions, ['api-access']));

        /*
        |--------------------------------------------------------------------------
        | USERS (ASSIGNED WEB + API ROLES CORRECTLY)
        |--------------------------------------------------------------------------
        */
        $users = [
            'super-admin' => 'superadmin@contentra.test',
            'admin'       => 'admin@contentra.test',
            'editor'      => 'editor@contentra.test',
            'author'      => 'author@contentra.test',
            'viewer'      => 'viewer@contentra.test',
        ];

        foreach ($users as $roleName => $email) {
            $user = User::create([
                'name' => ucfirst(str_replace('-', ' ', $roleName)),
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // Assign WEB role
            $user->assignRole(
                Role::where('name', $roleName)->where('guard_name', 'web')->first()
            );

            // Assign API role
            $user->assignRole(
                Role::where('name', $roleName)->where('guard_name', 'api')->first()
            );
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

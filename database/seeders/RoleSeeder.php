<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Define Permissions ---
        $permissions = [
            // Admin
            'manage-users',
            'manage-events',
            'view-all-data',

            // Event Manager
            'manage-projects',
            'assign-judges',

            // Judge
            'evaluate-projects',

            // Advisor
            'view-advised-projects',

            // Student
            'manage-own-projects',
            'manage-team',
            'upload-deliverables',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- 2. Create Roles and Assign Permissions ---

        // 1. Admin
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo(['manage-users', 'manage-events', 'view-all-data']);

        // 2. Event Manager
        $role = Role::firstOrCreate(['name' => 'event_manager']);
        $role->givePermissionTo(['manage-projects', 'assign-judges']);

        // 3. Judge
        $role = Role::firstOrCreate(['name' => 'judge']);
        $role->givePermissionTo(['evaluate-projects']);

        // 4. Advisor
        $role = Role::firstOrCreate(['name' => 'advisor']);
        $role->givePermissionTo(['view-advised-projects']);

        // 5. Student
        $role = Role::firstOrCreate(['name' => 'student']);
        $role->givePermissionTo(['manage-own-projects', 'manage-team', 'upload-deliverables']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage schools',
            'manage school subscriptions',
            'manage individual subscriptions',
            'manage users',
            'manage school users',
            'manage books',
            'add books',
            'edit books',
            'delete books',
            'view books',
            'manage authors',
            'manage publishers',
            'manage categories',
            'manage roles',
            'manage permissions',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $schoolAdmin = Role::firstOrCreate(['name' => 'School Admin', 'guard_name' => 'web']);
        $librarian = Role::firstOrCreate(['name' => 'Librarian/Admin', 'guard_name' => 'web']);
        $individual = Role::firstOrCreate(['name' => 'Individual User/Student', 'guard_name' => 'web']);
        $reader = Role::firstOrCreate(['name' => 'Reader', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());

        $schoolAdmin->syncPermissions([
            'view dashboard', 'manage school users', 'manage books', 'add books', 'edit books', 'delete books', 'view books', 'manage authors', 'manage publishers', 'manage categories', 'view reports'
        ]);

        $librarian->syncPermissions([
            'view dashboard', 'manage books', 'add books', 'edit books', 'view books', 'manage authors', 'manage publishers', 'manage categories'
        ]);

        $individual->syncPermissions(['view dashboard', 'view books']);
        $reader->syncPermissions(['view books']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SchoolAdminUserPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::firstOrCreate([
            'name' => 'manage users',
            'guard_name' => 'web',
        ]);

        $role = Role::where('name', 'School Admin')
            ->where('guard_name', 'web')
            ->first();

        if ($role) {
            $role->givePermissionTo($permission);
        }
    }
}
<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    public function roles()
    {
        return Role::orderBy('name')->get();
    }

    public function permissions()
    {
        return Permission::orderBy('name')->get();
    }

    public function getRolePermissions(int $roleId): array
    {
        return Role::findOrFail($roleId)
            ->permissions
            ->pluck('name')
            ->toArray();
    }

    public function syncPermissions(
        int $roleId,
        array $permissions
    ): void {
        $role = Role::findOrFail($roleId);

        $role->syncPermissions($permissions);

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}
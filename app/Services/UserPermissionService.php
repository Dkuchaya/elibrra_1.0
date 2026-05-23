<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserPermissionService
{
    public function users()
    {
        return User::with('roles')
            ->orderBy('first_name')
            ->get();
    }

    public function permissions()
    {
        return Permission::orderBy('name')->get();
    }

    public function getUserPermissions(int $userId): array
    {
        return User::findOrFail($userId)
            ->permissions
            ->pluck('name')
            ->toArray();
    }

    public function syncPermissions(int $userId, array $permissions): void
    {
        $user = User::findOrFail($userId);

        $user->syncPermissions($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
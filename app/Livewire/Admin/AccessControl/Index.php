<?php

namespace App\Livewire\Admin\AccessControl;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\RolePermissionService;
use App\Services\UserPermissionService;

#[Layout('layouts.app')]
class Index extends Component
{
    public $activeTab = 'roles';

    // Role permissions
    public $role_id;
    public array $selectedRolePermissions = [];

    // User permissions
    public $user_id;
    public array $selectedUserPermissions = [];

    /*
    |--------------------------------------------------------------------------
    | Role Permission Methods
    |--------------------------------------------------------------------------
    */

    public function updatedRoleId(): void
    {
        $service = app(RolePermissionService::class);

        $this->selectedRolePermissions = $this->role_id
            ? $service->getRolePermissions($this->role_id)
            : [];
    }

    public function saveRolePermissions(): void
    {
        $this->validate([
            'role_id' => 'required|exists:roles,id',
            'selectedRolePermissions' => 'array',
        ]);

        app(RolePermissionService::class)
            ->syncPermissions(
                $this->role_id,
                $this->selectedRolePermissions
            );

        session()->flash(
            'success',
            'Role permissions updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User Permission Methods
    |--------------------------------------------------------------------------
    */

    public function updatedUserId(): void
    {
        $service = app(UserPermissionService::class);

        $this->selectedUserPermissions = $this->user_id
            ? $service->getUserPermissions($this->user_id)
            : [];
    }

    public function saveUserPermissions(): void
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'selectedUserPermissions' => 'array',
        ]);

        app(UserPermissionService::class)
            ->syncPermissions(
                $this->user_id,
                $this->selectedUserPermissions
            );

        session()->flash(
            'success',
            'User permissions updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $roleService = app(RolePermissionService::class);
        $userService = app(UserPermissionService::class);

        return view('livewire.admin.access-control.index', [
            'roles' => $roleService->roles(),
            'users' => $userService->users(),
            'permissions' => $roleService->permissions(),
        ]);
    }
}
<?php

namespace App\Livewire\Admin\Roles;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\RolePermissionService;

#[Layout('layouts.app')]
class Permissions extends Component
{
    public $role_id;
    public array $selectedPermissions = [];

    public function updatedRoleId()
{
    $service = app(RolePermissionService::class);

    $this->selectedPermissions =
        $service->getRolePermissions($this->role_id);
}

   public function save()
{
    $this->validate([
        'role_id' => 'required',
    ]);

    $service = app(RolePermissionService::class);

    $service->syncPermissions(
        $this->role_id,
        $this->selectedPermissions
    );

    session()->flash(
        'success',
        'Permissions updated successfully.'
    );
}

    public function render()
{
    $service = app(RolePermissionService::class);

    return view(
        'livewire.admin.roles.permissions',
        [
            'roles' => $service->roles(),
            'permissions' => $service->permissions(),
        ]
    );
}
}

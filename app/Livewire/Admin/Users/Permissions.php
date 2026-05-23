<?php

namespace App\Livewire\Admin\Users;

use App\Services\UserPermissionService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Permissions extends Component
{
    public $user_id;
    public array $selectedPermissions = [];

    public function updatedUserId(): void
    {
        if (! $this->user_id) {
            $this->selectedPermissions = [];
            return;
        }

        $service = app(UserPermissionService::class);

        $this->selectedPermissions = $service->getUserPermissions($this->user_id);
    }

    public function save(): void
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'selectedPermissions' => 'array',
        ]);

        $service = app(UserPermissionService::class);

        $service->syncPermissions(
            $this->user_id,
            $this->selectedPermissions
        );

        session()->flash('success', 'User permissions updated successfully.');
    }

    public function render()
    {
        $service = app(UserPermissionService::class);

        return view('livewire.admin.users.permissions', [
            'users' => $service->users(),
            'permissions' => $service->permissions(),
        ]);
    }
}
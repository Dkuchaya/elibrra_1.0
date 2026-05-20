<?php

namespace App\Livewire\Admin\Users;

use App\Models\School;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $userId;
    public $school_id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $gender;
    public $city;
    public $role;
    public $is_active = true;

    public $search = '';
    public $isEditing = false;

    protected function rules()
    {
        return [
            'school_id' => auth()->user()->hasRole('Super Admin') ? 'nullable|exists:schools,id' : 'nullable',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ];
    }

    public function save(UserService $userService)
    {
        $data = $this->validate();

        $userService->create($data);

        $this->resetForm();

        session()->flash('success', 'User created successfully. Default password is password123.');
    }

    public function edit(UserService $userService, $id)
    {
        $user = $userService->find($id);

        $this->userId = $user->id;
        $this->school_id = $user->school_id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->gender = $user->gender;
        $this->city = $user->city;
        $this->role = $user->roles->first()?->name;
        $this->is_active = $user->is_active;
        $this->isEditing = true;
    }

    public function update(UserService $userService)
    {
        $data = $this->validate();

        $userService->update($this->userId, $data);

        $this->resetForm();

        session()->flash('success', 'User updated successfully.');
    }

    public function toggleStatus(UserService $userService, $id)
    {
        $userService->toggleStatus($id);

        session()->flash('success', 'User status updated.');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Delete User?',
            'text' => 'This action cannot be undone.',
            'event' => 'deleteUserConfirmed',
            'id' => $id,
        ]);
    }

    #[On('deleteUserConfirmed')]
    public function deleteUserConfirmed($id, UserService $userService)
    {
        $userService->delete($id);

        $this->resetPage();

        session()->flash('success', 'User deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'school_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'gender',
            'city',
            'role',
            'is_active',
            'isEditing',
        ]);

        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(UserService $userService)
    {
        $roles = Role::query()
            ->when(auth()->user()->hasRole('School Admin'), function ($query) {
                $query->whereIn('name', [
                    'Librarian/Admin',
                    'Individual User/Student',
                    'Reader',
                ]);
            })
            ->get();

        return view('livewire.admin.users.index', [
            'users' => $userService->paginate($this->search),
            'schools' => School::orderBy('name')->get(),
            'roles' => $roles,
        ]);
    }
}
<?php

namespace App\Livewire\Admin\Users;

use App\Models\School;
use App\Models\User;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $csv_file;
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

    private array $schoolAdminRoles = [
        'Reader',
        'Individual User/Student',
    ];

    public function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole('Super Admin');
    }

    protected function rules()
    {
        return [
            'school_id' => $this->isSuperAdmin()
                ? 'nullable|exists:schools,id'
                : 'nullable',

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

    private function prepareData(array $data): array
    {
        if (! $this->isSuperAdmin()) {
            $data['school_id'] = auth()->user()->school_id;

            if (! in_array($data['role'], $this->schoolAdminRoles)) {
                abort(403, 'School Admin can only create readers/students.');
            }
        }

        return $data;
    }

    private function authorizeUserAccess(User $user): void
    {
        if ($this->isSuperAdmin()) {
            return;
        }

        abort_if($user->school_id !== auth()->user()->school_id, 403);
    }

    public function save(UserService $userService)
    {
        $data = $this->prepareData($this->validate());

        $userService->create($data);

        $this->resetForm();

        session()->flash('success', 'User created successfully. Default password is password123.');
    }

    public function edit(UserService $userService, $id)
    {
        $user = $userService->find($id);

        $this->authorizeUserAccess($user);

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
        $user = $userService->find($this->userId);

        $this->authorizeUserAccess($user);

        $data = $this->prepareData($this->validate());

        $userService->update($this->userId, $data);

        $this->resetForm();

        session()->flash('success', 'User updated successfully.');
    }

    public function toggleStatus(UserService $userService, $id)
    {
        $user = $userService->find($id);

        $this->authorizeUserAccess($user);

        $userService->toggleStatus($id);

        session()->flash('success', 'User status updated.');
    }

    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);

        $this->authorizeUserAccess($user);

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
        $user = $userService->find($id);

        $this->authorizeUserAccess($user);

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
            ->when(! $this->isSuperAdmin(), function ($query) {
                $query->whereIn('name', $this->schoolAdminRoles);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.users.index', [
            'users' => $userService->paginate(
                        $this->search,
                        10,
                        auth()->user()
                    ),
            'schools' => $this->isSuperAdmin()
                ? School::orderBy('name')->get()
                : collect(),
            'roles' => $roles,
        ]);
    }

    public function importCsv(UserService $userService)
{
    $this->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $result = $userService->importCsv(
        $this->csv_file,
        auth()->user()
    );

    $this->csv_file = null;

    session()->flash(
        'success',
        "CSV import completed. Created: {$result['created']}, Skipped: {$result['skipped']}."
    );
}
}
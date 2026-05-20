<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $users
    ) {}

    public function paginate(string $search = '', int $perPage = 10)
    {
        return $this->users->paginate($search, $perPage);
    }

    public function find(int $id): User
    {
        return $this->users->find($id);
    }

    public function create(array $data): User
    {
        $role = $data['role'];

        unset($data['role']);

        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['password'] = Hash::make('password123');
        $data['must_change_password'] = true;

        if (auth()->user()->hasRole('School Admin')) {
            $data['school_id'] = auth()->user()->school_id;
        }

        $user = $this->users->create($data);

        $user->assignRole($role);

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $role = $data['role'];

        unset($data['role']);

        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);

        if (auth()->user()->hasRole('School Admin')) {
            $data['school_id'] = auth()->user()->school_id;
        }

        $user = $this->users->update($id, $data);

        $user->syncRoles([$role]);

        return $user;
    }

    public function toggleStatus(int $id): User
    {
        $user = $this->users->find($id);

        return $this->users->update($id, [
            'is_active' => ! $user->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->users->delete($id);
    }
}
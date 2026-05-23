<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function paginate(string $search = '', int $perPage = 10, ?User $authUser = null)
{
    return User::with(['school', 'roles'])
        ->when($authUser && ! $authUser->hasRole('Super Admin'), function ($query) use ($authUser) {
            $query->where('school_id', $authUser->school_id);
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate($perPage);
}

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
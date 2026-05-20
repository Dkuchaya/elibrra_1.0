<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function paginate(string $search = '', int $perPage = 10)
    {
        return User::query()
            ->with(['school', 'roles'])
            ->when(auth()->user()->hasRole('School Admin'), function ($query) {
                $query->where('school_id', auth()->user()->school_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
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
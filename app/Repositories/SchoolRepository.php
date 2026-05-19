<?php

namespace App\Repositories;

use App\Models\School;

class SchoolRepository
{
    public function paginate(string $search = '', int $perPage = 10)
    {
        return School::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): School
    {
        return School::create($data);
    }

    public function find(int $id): School
    {
        return School::findOrFail($id);
    }

    public function update(int $id, array $data): School
    {
        $school = $this->find($id);
        $school->update($data);

        return $school;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
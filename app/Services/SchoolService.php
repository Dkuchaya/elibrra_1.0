<?php

namespace App\Services;

use App\Models\School;
use App\Repositories\SchoolRepository;
use Illuminate\Support\Str;

class SchoolService
{
    public function __construct(
        protected SchoolRepository $schools
    ) {}

    public function find(int $id): School
{
    return $this->schools->find($id);
}

    public function paginate(string $search = '', int $perPage = 10)
    {
        return $this->schools->paginate($search, $perPage);
    }

    public function create(array $data): School
    {
        $data['slug'] = Str::slug($data['name'] . '-' . time());

        return $this->schools->create($data);
    }

    public function update(int $id, array $data): School
    {
        $data['slug'] = Str::slug($data['name'] . '-' . $id);

        return $this->schools->update($id, $data);
    }

    public function toggleStatus(int $id): School
    {
        $school = $this->schools->find($id);

        return $this->schools->update($id, [
            'is_active' => ! $school->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->schools->delete($id);
    }
}
<?php

namespace App\Repositories;

use App\Models\Publisher;

class PublisherRepository
{
    public function paginate(string $search = '', int $perPage = 10)
    {
        return Publisher::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): Publisher
    {
        return Publisher::findOrFail($id);
    }

    public function create(array $data): Publisher
    {
        return Publisher::create($data);
    }

    public function update(int $id, array $data): Publisher
    {
        $publisher = $this->find($id);
        $publisher->update($data);

        return $publisher;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
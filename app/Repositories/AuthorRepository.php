<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository
{
    public function paginate(string $search = '', int $perPage = 10)
    {
        return Author::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): Author
    {
        return Author::findOrFail($id);
    }

    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function update(int $id, array $data): Author
    {
        $author = $this->find($id);
        $author->update($data);

        return $author;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
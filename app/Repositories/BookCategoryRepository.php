<?php

namespace App\Repositories;

use App\Models\BookCategory;

class BookCategoryRepository
{
    public function paginate(string $search = '', int $perPage = 10)
    {
        return BookCategory::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): BookCategory
    {
        return BookCategory::findOrFail($id);
    }

    public function create(array $data): BookCategory
    {
        return BookCategory::create($data);
    }

    public function update(int $id, array $data): BookCategory
    {
        $category = $this->find($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
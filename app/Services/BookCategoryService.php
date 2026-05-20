<?php

namespace App\Services;

use App\Models\BookCategory;
use App\Repositories\BookCategoryRepository;
use Illuminate\Support\Str;

class BookCategoryService
{
    public function __construct(
        protected BookCategoryRepository $categories
    ) {}

    public function paginate(string $search = '', int $perPage = 10)
    {
        return $this->categories->paginate($search, $perPage);
    }

    public function find(int $id): BookCategory
    {
        return $this->categories->find($id);
    }

    public function create(array $data): BookCategory
    {
        $data['slug'] = Str::slug($data['name'] . '-' . time());

        return $this->categories->create($data);
    }

    public function update(int $id, array $data): BookCategory
    {
        $data['slug'] = Str::slug($data['name'] . '-' . $id);

        return $this->categories->update($id, $data);
    }

    public function toggleStatus(int $id): BookCategory
    {
        $category = $this->categories->find($id);

        return $this->categories->update($id, [
            'is_active' => ! $category->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->categories->delete($id);
    }
}
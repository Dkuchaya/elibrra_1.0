<?php

namespace App\Services;

use App\Models\Author;
use App\Repositories\AuthorRepository;
use Illuminate\Support\Str;

class AuthorService
{
    public function __construct(
        protected AuthorRepository $authors
    ) {}

    public function paginate(string $search = '', int $perPage = 10)
    {
        return $this->authors->paginate($search, $perPage);
    }

    public function find(int $id): Author
    {
        return $this->authors->find($id);
    }

    public function create(array $data): Author
    {
        $data['slug'] = Str::slug($data['name'] . '-' . time());

        return $this->authors->create($data);
    }

    public function update(int $id, array $data): Author
    {
        $data['slug'] = Str::slug($data['name'] . '-' . $id);

        return $this->authors->update($id, $data);
    }

    public function toggleStatus(int $id): Author
    {
        $author = $this->authors->find($id);

        return $this->authors->update($id, [
            'is_active' => ! $author->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->authors->delete($id);
    }
}
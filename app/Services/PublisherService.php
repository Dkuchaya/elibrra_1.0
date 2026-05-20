<?php

namespace App\Services;

use App\Models\Publisher;
use App\Repositories\PublisherRepository;
use Illuminate\Support\Str;

class PublisherService
{
    public function __construct(
        protected PublisherRepository $publishers
    ) {}

    public function paginate(string $search = '', int $perPage = 10)
    {
        return $this->publishers->paginate($search, $perPage);
    }

    public function find(int $id): Publisher
    {
        return $this->publishers->find($id);
    }

    public function create(array $data): Publisher
    {
        $data['slug'] = Str::slug($data['name'] . '-' . time());

        return $this->publishers->create($data);
    }

    public function update(int $id, array $data): Publisher
    {
        $data['slug'] = Str::slug($data['name'] . '-' . $id);

        return $this->publishers->update($id, $data);
    }

    public function toggleStatus(int $id): Publisher
    {
        $publisher = $this->publishers->find($id);

        return $this->publishers->update($id, [
            'is_active' => ! $publisher->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->publishers->delete($id);
    }
}
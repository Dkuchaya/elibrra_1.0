<?php

namespace App\Services;

use App\Models\Book;

class LibraryService
{
    public function books(array $filters = [])
    {
        return Book::with(['authors', 'category'])
            ->where('is_active', true)
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('legacy_isbn', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->where('book_category_id', $category);
            })
            ->when(($filters['sort'] ?? 'title') === 'title', function ($query) {
                $query->orderBy('title');
            })
            ->when(($filters['sort'] ?? '') === 'latest', function ($query) {
                $query->latest();
            })
            ->when(($filters['sort'] ?? '') === 'popular', function ($query) {
                $query->orderByDesc('views');
            });
    }
}
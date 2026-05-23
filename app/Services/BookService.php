<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookService
{
    public function create(array $data, $coverFile = null, $bookFile = null): Book
    {
        $authorIds = $data['author_ids'] ?? [];
        unset($data['author_ids']);

        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['views'] = $data['views'] ?? 0;

        if ($coverFile) {
            $data['cover_image'] = $coverFile->store('books/covers', 'public');
        }

        if ($bookFile) {
            $data['pdf_path'] = $bookFile->store('books/files', 'public');
            $data['file_size'] = $bookFile->getSize();
        }

        $book = Book::create($data);

        $book->authors()->sync($authorIds);

        return $book;
    }

    public function update(Book $book, array $data, $coverFile = null, $bookFile = null): Book
    {
        $authorIds = $data['author_ids'] ?? [];
        unset($data['author_ids'], $data['slug']);

        if ($coverFile) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $data['cover_image'] = $coverFile->store('books/covers', 'public');
        }

        if ($bookFile) {
            if ($book->pdf_path) {
                Storage::disk('public')->delete($book->pdf_path);
            }

            $data['pdf_path'] = $bookFile->store('books/files', 'public');
            $data['file_size'] = $bookFile->getSize();
        }

        $book->update($data);
        $book->authors()->sync($authorIds);

        return $book;
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }

    public function toggleStatus(Book $book): Book
    {
        $book->update([
            'is_active' => ! $book->is_active,
        ]);

        return $book;
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
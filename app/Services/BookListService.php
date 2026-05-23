<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookList;
use App\Models\User;
use Illuminate\Support\Str;

class BookListService
{
    public function myLists(User $user)
    {
        return BookList::withCount('books')
            ->where('user_id', $user->id)
            ->latest();
    }

    public function create(User $user, array $data): BookList
    {
        return BookList::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'visibility' => $data['visibility'] ?? 'private',
            'is_active' => true,
        ]);
    }

    public function update(BookList $list, array $data): BookList
    {
        $list->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'visibility' => $data['visibility'] ?? 'private',
        ]);

        return $list;
    }

    public function delete(BookList $list): void
    {
        $list->delete();
    }

    public function addBook(BookList $list, Book $book): void
    {
        $maxOrder = $list->books()->max('book_list_items.sort_order') ?? 0;

        $list->books()->syncWithoutDetaching([
            $book->id => [
                'sort_order' => $maxOrder + 1,
            ],
        ]);
    }

    public function removeBook(BookList $list, Book $book): void
    {
        $list->books()->detach($book->id);
    }

    public function canView(BookList $list, ?User $user): bool
    {
        if ($list->visibility === 'public' || $list->visibility === 'unlisted') {
            return true;
        }

        return $user && $list->user_id === $user->id;
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (BookList::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
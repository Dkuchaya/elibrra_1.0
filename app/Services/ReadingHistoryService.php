<?php

namespace App\Services;

use App\Models\Book;
use App\Models\ReadingHistory;
use App\Models\User;

class ReadingHistoryService
{
    public function updateProgress(User $user, Book $book, int $page): ReadingHistory
    {
        return ReadingHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ],
            [
                'last_page' => max(1, $page),
                'last_read_at' => now(),
            ]
        );
    }

    public function getLastPage(User $user, Book $book): int
    {
        return ReadingHistory::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->value('last_page') ?? 1;
    }

    public function myHistory(User $user)
    {
        return ReadingHistory::with(['book.authors', 'book.category'])
            ->where('user_id', $user->id)
            ->latest('last_read_at');
    }
}
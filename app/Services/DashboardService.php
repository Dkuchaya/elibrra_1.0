<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\ReadingHistory;
use App\Models\School;
use App\Models\User;

class DashboardService
{
    public function summary(): array
    {
        return [
            'totalSchools' => School::count(),
            'totalUsers' => User::count(),
            'totalBooks' => Book::count(),
            'totalCategories' => BookCategory::count(),

            'booksReadToday' => ReadingHistory::whereDate('last_read_at', today())
                ->distinct('book_id')
                ->count('book_id'),

            'booksReadThisWeek' => ReadingHistory::whereBetween('last_read_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])
                ->distinct('book_id')
                ->count('book_id'),

            'booksReadThisMonth' => ReadingHistory::whereMonth('last_read_at', now()->month)
                ->whereYear('last_read_at', now()->year)
                ->distinct('book_id')
                ->count('book_id'),

            'mostReadBooks' => Book::orderByDesc('views')
                ->take(5)
                ->get(),

            'topCategories' => BookCategory::withCount('books')
                ->orderByDesc('books_count')
                ->take(5)
                ->get(),
        ];
    }
}
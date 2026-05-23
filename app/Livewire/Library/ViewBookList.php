<?php

namespace App\Livewire\Library;

use App\Models\Book;
use App\Models\BookList;
use App\Services\BookListService;
use Livewire\Component;

class ViewBookList extends Component
{
    public BookList $list;

    public function mount($slug): void
    {
        $this->list = BookList::with(['user', 'books.authors', 'books.category'])
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless(
            app(BookListService::class)->canView($this->list, auth()->user()),
            403
        );
    }

    public function removeBook($bookId): void
    {
        abort_unless($this->list->user_id === auth()->id(), 403);

        app(BookListService::class)->removeBook(
            $this->list,
            Book::findOrFail($bookId)
        );

        $this->list->load(['books.authors', 'books.category']);

        session()->flash('success', 'Book removed from list.');
    }

    public function render()
    {
        return view('livewire.library.view-book-list')
            ->layout('layouts.app');
    }
}
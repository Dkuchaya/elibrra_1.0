<?php

namespace App\Livewire\Library;

use App\Models\Book;
use App\Services\BookAccessService;
use Livewire\Component;
use App\Services\ReadingHistoryService;

class BookReader extends Component
{
    public Book $book;
    public int $lastPage = 1;

    public bool $canRead = false;

    public function mount($slug): void
    {
        $this->book = Book::where('slug', $slug)->firstOrFail();

        $service = app(BookAccessService::class);

        $this->canRead = $service->canRead($this->book, auth()->user());

        if ($this->canRead) {
             $service->recordView($this->book);

            $this->lastPage = app(ReadingHistoryService::class)
                ->getLastPage(auth()->user(), $this->book);
        }
        
    }

    public function saveProgress(int $page): void
{
    if (! auth()->check()) {
        return;
    }

    if (! $this->canRead) {
        return;
    }

    app(ReadingHistoryService::class)->updateProgress(
        auth()->user(),
        $this->book,
        $page
    );
}

    public function render()
    {
        return view('livewire.library.book-reader')
            ->layout('layouts.app');
    }
}
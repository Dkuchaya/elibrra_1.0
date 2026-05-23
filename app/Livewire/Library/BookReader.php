<?php

namespace App\Livewire\Library;

use App\Models\Book;
use App\Services\BookAccessService;
use Livewire\Component;

class BookReader extends Component
{
    public Book $book;

    public bool $canRead = false;

    public function mount($slug): void
    {
        $this->book = Book::where('slug', $slug)->firstOrFail();

        $service = app(BookAccessService::class);

        $this->canRead = $service->canRead($this->book, auth()->user());

        if ($this->canRead) {
            $service->recordView($this->book);
        }
    }

    public function render()
    {
        return view('livewire.library.book-reader')
            ->layout('layouts.app');
    }
}
<?php

namespace App\Livewire\Library;

use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\LibraryService;
use App\Models\Book;
use App\Models\BookList;
use App\Services\BookListService;



class Books extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'title';
    public $category = '';
    public $selectedBookId;
    public $selectedListId;
    public $addToListModalOpen = false;

    public function openAddToListModal($bookId): void
    {
        $this->selectedBookId = $bookId;
        $this->selectedListId = null;
        $this->addToListModalOpen = true;
    }

    public function addBookToList(): void
    {
        $this->validate([
            'selectedBookId' => 'required|exists:books,id',
            'selectedListId' => 'required|exists:book_lists,id',
        ]);

        $list = BookList::where('user_id', auth()->id())
            ->findOrFail($this->selectedListId);

        $book = Book::findOrFail($this->selectedBookId);

        app(BookListService::class)->addBook($list, $book);

        $this->addToListModalOpen = false;
        $this->selectedBookId = null;
        $this->selectedListId = null;

        session()->flash('success', 'Book added to your list.');
    }

   public function render()
{
    $libraryService = app(LibraryService::class);

    $books = $libraryService->books([
        'search' => $this->search,
        'category' => $this->category,
        'sort' => $this->sort,
    ])->paginate(12);

    return view('livewire.library.books', [
        'books' => $books,
        'categories' => BookCategory::orderBy('name')->get(),
        'myLists' => BookList::where('user_id', auth()->id())
        ->latest()
        ->get(),
    ])->layout('layouts.app');
}
}
<?php

namespace App\Livewire\Library;

use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\LibraryService;



class Books extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'title';
    public $category = '';

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
    ])->layout('layouts.app');
}
}
<?php

namespace App\Livewire\Admin\Books;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Publisher;
use App\Services\BookService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $book_id;
    public $legacy_isbn;
    public $publisher_id;
    public $book_category_id;
    public $author_ids = [];

    public $isbn;
    public $title;
    public $description;
    public $cover_image;
    public $pdf_path;

    public $new_cover_image;
    public $new_pdf_file;

    public $pages;
    public $published_year;
    public $language = 'English';
    public $file_size;
    public $views = 0;
    public $featured = false;
    public $edition;
    public $book_type = 'pdf';
    public $subscription_required = false;
    public $visibility = 'public';
    public $is_active = true;

    public $search = '';
    public $modalOpen = false;

    protected function rules(): array
    {
        return [
            'legacy_isbn' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'book_category_id' => 'nullable|exists:book_categories,id',

            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',

            'isbn' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'new_cover_image' => 'nullable|image|max:2048',
            'new_pdf_file' => 'nullable|mimes:pdf,epub,mp3,wav,m4a|max:51200',

            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'language' => 'required|string|max:255',
            'featured' => 'boolean',
            'edition' => 'nullable|string|max:255',
            'book_type' => 'required|in:pdf,epub,audio',
            'subscription_required' => 'boolean',
            'visibility' => 'required|in:public,school,restricted',
            'is_active' => 'boolean',
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->modalOpen = true;
    }

    public function edit($id): void
    {
        $book = Book::with('authors')->findOrFail($id);

        $this->book_id = $book->id;
        $this->legacy_isbn = $book->legacy_isbn;
        $this->publisher_id = $book->publisher_id;
        $this->book_category_id = $book->book_category_id;
        $this->author_ids = $book->authors->pluck('id')->toArray();

        $this->isbn = $book->isbn;
        $this->title = $book->title;
        $this->description = $book->description;
        $this->cover_image = $book->cover_image;
        $this->pdf_path = $book->pdf_path;

        $this->pages = $book->pages;
        $this->published_year = $book->published_year;
        $this->language = $book->language;
        $this->file_size = $book->file_size;
        $this->views = $book->views;
        $this->featured = $book->featured;
        $this->edition = $book->edition;
        $this->book_type = $book->book_type;
        $this->subscription_required = $book->subscription_required;
        $this->visibility = $book->visibility;
        $this->is_active = $book->is_active;

        $this->modalOpen = true;
    }

    public function save(BookService $bookService): void
    {
        $this->validate();

        $data = $this->formData();

        if ($this->book_id) {
            $book = Book::findOrFail($this->book_id);

            $bookService->update(
                $book,
                $data,
                $this->new_cover_image,
                $this->new_pdf_file
            );

            session()->flash('success', 'Book updated successfully.');
        } else {
            $bookService->create(
                $data,
                $this->new_cover_image,
                $this->new_pdf_file
            );

            session()->flash('success', 'Book created successfully.');
        }

        $this->modalOpen = false;
        $this->resetForm();
    }

    public function delete($id, BookService $bookService): void
    {
        $book = Book::findOrFail($id);

        $bookService->delete($book);

        session()->flash('success', 'Book deleted successfully.');
    }

    public function toggleStatus($id, BookService $bookService): void
    {
        $book = Book::findOrFail($id);

        $bookService->toggleStatus($book);
    }

    private function formData(): array
    {
        return [
            'legacy_isbn' => $this->legacy_isbn,
            'publisher_id' => $this->publisher_id,
            'book_category_id' => $this->book_category_id,
            'author_ids' => $this->author_ids,

            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,

            'pages' => $this->pages,
            'published_year' => $this->published_year,
            'language' => $this->language,
            'views' => $this->views ?? 0,
            'featured' => $this->featured,
            'edition' => $this->edition,
            'book_type' => $this->book_type,
            'subscription_required' => $this->subscription_required,
            'visibility' => $this->visibility,
            'is_active' => $this->is_active,
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'book_id',
            'legacy_isbn',
            'publisher_id',
            'book_category_id',
            'author_ids',
            'isbn',
            'title',
            'description',
            'cover_image',
            'pdf_path',
            'new_cover_image',
            'new_pdf_file',
            'pages',
            'published_year',
            'file_size',
            'views',
            'featured',
            'edition',
            'subscription_required',
        ]);

        $this->language = 'English';
        $this->book_type = 'pdf';
        $this->visibility = 'public';
        $this->is_active = true;
        $this->author_ids = [];
    }

    public function render()
    {
        return view('livewire.admin.books.index', [
            'books' => Book::with(['publisher', 'category', 'authors'])
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn', 'like', '%' . $this->search . '%')
                        ->orWhere('legacy_isbn', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),

            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => BookCategory::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
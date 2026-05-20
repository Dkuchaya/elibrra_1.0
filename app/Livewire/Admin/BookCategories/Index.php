<?php

namespace App\Livewire\Admin\BookCategories;

use App\Services\BookCategoryService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $categoryId;
    public $name;
    public $description;
    public $is_active = true;

    public $search = '';
    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function save(BookCategoryService $categoryService)
    {
        $data = $this->validate();

        $categoryService->create($data);

        $this->resetForm();

        session()->flash('success', 'Book category created successfully.');
    }

    public function edit(BookCategoryService $categoryService, $id)
    {
        $category = $categoryService->find($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->isEditing = true;
    }

    public function update(BookCategoryService $categoryService)
    {
        $data = $this->validate();

        $categoryService->update($this->categoryId, $data);

        $this->resetForm();

        session()->flash('success', 'Book category updated successfully.');
    }

    public function toggleStatus(BookCategoryService $categoryService, $id)
    {
        $categoryService->toggleStatus($id);

        session()->flash('success', 'Book category status updated.');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Delete Category?',
            'text' => 'This action cannot be undone.',
            'event' => 'deleteBookCategoryConfirmed',
            'id' => $id,
        ]);
    }

    #[On('deleteBookCategoryConfirmed')]
    public function deleteBookCategoryConfirmed($id, BookCategoryService $categoryService)
    {
        $categoryService->delete($id);

        $this->resetPage();

        session()->flash('success', 'Book category deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'description',
            'is_active',
            'isEditing',
        ]);

        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(BookCategoryService $categoryService)
    {
        return view('livewire.admin.book-categories.index', [
            'categories' => $categoryService->paginate($this->search),
        ]);
    }
}
<?php

namespace App\Livewire\Admin\Authors;

use App\Services\AuthorService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $authorId;
    public $name;
    public $biography;
    public $is_active = true;

    public $search = '';
    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function save(AuthorService $authorService)
    {
        $data = $this->validate();

        $authorService->create($data);

        $this->resetForm();

        session()->flash('success', 'Author created successfully.');
    }

    public function edit(AuthorService $authorService, $id)
    {
        $author = $authorService->find($id);

        $this->authorId = $author->id;
        $this->name = $author->name;
        $this->biography = $author->biography;
        $this->is_active = $author->is_active;
        $this->isEditing = true;
    }

    public function update(AuthorService $authorService)
    {
        $data = $this->validate();

        $authorService->update($this->authorId, $data);

        $this->resetForm();

        session()->flash('success', 'Author updated successfully.');
    }

    public function toggleStatus(AuthorService $authorService, $id)
    {
        $authorService->toggleStatus($id);

        session()->flash('success', 'Author status updated.');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Delete Author?',
            'text' => 'This action cannot be undone.',
            'event' => 'deleteAuthorConfirmed',
            'id' => $id,
        ]);
    }

    #[On('deleteAuthorConfirmed')]
    public function deleteAuthorConfirmed($id, AuthorService $authorService)
    {
        $authorService->delete($id);

        $this->resetPage();

        session()->flash('success', 'Author deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'authorId',
            'name',
            'biography',
            'is_active',
            'isEditing',
        ]);

        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(AuthorService $authorService)
    {
        return view('livewire.admin.authors.index', [
            'authors' => $authorService->paginate($this->search),
        ]);
    }
}
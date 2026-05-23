<?php

namespace App\Livewire\Library;

use App\Models\BookList;
use App\Services\BookListService;
use Livewire\Component;
use Livewire\WithPagination;

class MyLists extends Component
{
    use WithPagination;

    public $list_id;
    public $name;
    public $description;
    public $visibility = 'private';

    public $modalOpen = false;
    public $isEditing = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:private,public,unlisted',
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->modalOpen = true;
    }

    public function edit($id): void
    {
        $list = BookList::where('user_id', auth()->id())->findOrFail($id);

        $this->list_id = $list->id;
        $this->name = $list->name;
        $this->description = $list->description;
        $this->visibility = $list->visibility;
        $this->isEditing = true;
        $this->modalOpen = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        $service = app(BookListService::class);

        if ($this->isEditing) {
            $list = BookList::where('user_id', auth()->id())
                ->findOrFail($this->list_id);

            $service->update($list, $data);

            session()->flash('success', 'Book list updated successfully.');
        } else {
            $service->create(auth()->user(), $data);

            session()->flash('success', 'Book list created successfully.');
        }

        $this->resetForm();
        $this->modalOpen = false;
    }

    public function delete($id): void
    {
        $list = BookList::where('user_id', auth()->id())->findOrFail($id);

        app(BookListService::class)->delete($list);

        session()->flash('success', 'Book list deleted successfully.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'list_id',
            'name',
            'description',
            'visibility',
            'modalOpen',
            'isEditing',
        ]);

        $this->visibility = 'private';
    }

    public function render()
    {
        return view('livewire.library.my-lists', [
            'lists' => app(BookListService::class)
                ->myLists(auth()->user())
                ->paginate(12),
        ])->layout('layouts.app');
    }
}
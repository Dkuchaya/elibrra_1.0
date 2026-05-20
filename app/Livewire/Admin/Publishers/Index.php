<?php

namespace App\Livewire\Admin\Publishers;

use App\Services\PublisherService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $publisherId;

    public $name;
    public $email;
    public $phone;
    public $address;
    public $is_active = true;

    public $search = '';
    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function save(PublisherService $publisherService)
    {
        $data = $this->validate();

        $publisherService->create($data);

        $this->resetForm();

        session()->flash('success', 'Publisher created successfully.');
    }

    public function edit(PublisherService $publisherService, $id)
    {
        $publisher = $publisherService->find($id);

        $this->publisherId = $publisher->id;
        $this->name = $publisher->name;
        $this->email = $publisher->email;
        $this->phone = $publisher->phone;
        $this->address = $publisher->address;
        $this->is_active = $publisher->is_active;

        $this->isEditing = true;
    }

    public function update(PublisherService $publisherService)
    {
        $data = $this->validate();

        $publisherService->update($this->publisherId, $data);

        $this->resetForm();

        session()->flash('success', 'Publisher updated successfully.');
    }

    public function toggleStatus(PublisherService $publisherService, $id)
    {
        $publisherService->toggleStatus($id);

        session()->flash('success', 'Publisher status updated.');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Delete Publisher?',
            'text' => 'This action cannot be undone.',
            'event' => 'deletePublisherConfirmed',
            'id' => $id,
        ]);
    }

    #[On('deletePublisherConfirmed')]
    public function deletePublisherConfirmed($id, PublisherService $publisherService)
    {
        $publisherService->delete($id);

        $this->resetPage();

        session()->flash('success', 'Publisher deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'publisherId',
            'name',
            'email',
            'phone',
            'address',
            'is_active',
            'isEditing',
        ]);

        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(PublisherService $publisherService)
    {
        return view('livewire.admin.publishers.index', [
            'publishers' => $publisherService->paginate($this->search),
        ]);
    }
}
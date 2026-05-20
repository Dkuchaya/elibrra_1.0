<?php

namespace App\Livewire\Admin\Schools;

use App\Services\SchoolService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
   use WithPagination, WithFileUploads;

    public $schoolId;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $is_active = true;
    public $logo;
    public $existingLogo;

    public $search = '';
    public $isEditing = false;

    protected $listeners = [
    'deleteSchoolConfirmed',
];

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

    public function save(SchoolService $schoolService)
    {
        $data = $this->validate();

        $schoolService->create($data);

        $this->resetForm();

        // session()->flash('success', 'School created successfully.');
        $this->dispatch('swal', [
            'title' => 'Success',
            'text' => 'School created successfully.',
            'icon' => 'success',

        ]);
    }

    public function edit(SchoolService $schoolService, $id)
    {
        $school = $schoolService->paginate()
            ->getCollection()
            ->firstWhere('id', $id);

        if (! $school) {
            $school = \App\Models\School::findOrFail($id);
        }

        $this->schoolId = $school->id;
        $this->name = $school->name;
        $this->email = $school->email;
        $this->phone = $school->phone;
        $this->address = $school->address;
        $this->existingLogo = $school->logo;
        $this->is_active = $school->is_active;
        $this->isEditing = true;
    }

    public function update(SchoolService $schoolService)
    {
        $data = $this->validate();

        $schoolService->update($this->schoolId, $data);

        $this->resetForm();

        // session()->flash('success', 'School updated successfully.');
        $this->dispatch('swal', [

        'title' => 'Success',
        'text' => 'School created successfully.',
        'icon' => 'success',

]);
    }

    public function toggleStatus(SchoolService $schoolService, $id)
    {
        $schoolService->toggleStatus($id);

        // session()->flash('success', 'School status updated.');
        $this->dispatch('swal', [

            'title' => 'Success',
            'text' => 'School updated successfully.',
            'icon' => 'success',

        ]);
    }

    

 public function confirmDelete($id)
{
    $this->dispatch('confirm-delete', id: $id);
}

#[On('deleteSchoolConfirmed')]
public function deleteSchoolConfirmed($id)
{
    app(SchoolService::class)->delete($id);
    $this->resetPage();
}
    public function resetForm()
    {
        $this->reset([
            'schoolId',
            'name',
            'email',
            'phone',
            'address',
            'is_active',
            'isEditing',
            'logo',
            'existingLogo',
        ]);

        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(SchoolService $schoolService)
    {
        return view('livewire.admin.schools.index', [
            'schools' => $schoolService->paginate($this->search),
        ]);
    }
}
<?php

namespace App\Livewire\Admin\Subscriptions\Plans;

use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $plan_id;
    public $name;
    public $type = 'school';
    public $price = 0;
    public $duration_days = 365;
    public $grace_period_days = 7;
    public $is_active = true;

    public $search = '';
    public $modalOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:school,individual',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'grace_period_days' => 'required|integer|min:0',
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
        $plan = SubscriptionPlan::findOrFail($id);

        $this->plan_id = $plan->id;
        $this->name = $plan->name;
        $this->type = $plan->type;
        $this->price = $plan->price;
        $this->duration_days = $plan->duration_days;
        $this->grace_period_days = $plan->grace_period_days;
        $this->is_active = $plan->is_active;

        $this->modalOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        SubscriptionPlan::updateOrCreate(
            ['id' => $this->plan_id],
            [
                'name' => $this->name,
                'type' => $this->type,
                'price' => $this->price,
                'duration_days' => $this->duration_days,
                'grace_period_days' => $this->grace_period_days,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('success', $this->plan_id ? 'Plan updated successfully.' : 'Plan created successfully.');

        $this->modalOpen = false;
        $this->resetForm();
    }

    public function toggleStatus($id): void
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $plan->update([
            'is_active' => ! $plan->is_active,
        ]);
    }

    public function delete($id): void
    {
        SubscriptionPlan::findOrFail($id)->delete();

        session()->flash('success', 'Plan deleted successfully.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'plan_id',
            'name',
            'price',
            'duration_days',
            'grace_period_days',
            'is_active',
        ]);

        $this->type = 'school';
        $this->price = 0;
        $this->duration_days = 365;
        $this->grace_period_days = 7;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.plans.index', [
            'plans' => SubscriptionPlan::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app');
    }
}
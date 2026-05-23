<?php

namespace App\Livewire\Admin\Subscriptions\Schools;
use App\Services\SchoolSubscriptionService;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $subscription_id;
    public $school_id;
    public $subscription_plan_id;
    public $starts_at;
    public $expires_at;
    public $grace_period_days = 7;
    public $status = 'active';
    public $amount_paid = 0;
    public $payment_reference;
    public $paid_at;

    public $search = '';
    public $modalOpen = false;

    protected function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after_or_equal:starts_at',
            'grace_period_days' => 'required|integer|min:0',
            'status' => 'required|in:active,expired,cancelled',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->starts_at = now()->format('Y-m-d');
        $this->paid_at = now()->format('Y-m-d');
        $this->modalOpen = true;
    }

   public function updatedSubscriptionPlanId($value, SchoolSubscriptionService $service): void
{
    if (! $value) {
        return;
    }

    $this->starts_at = $this->starts_at ?: now()->format('Y-m-d');

    $data = $service->calculateDates($value, $this->starts_at);

    $this->expires_at = $data['expires_at'];
    $this->grace_period_days = $data['grace_period_days'];
    $this->amount_paid = $data['amount_paid'];
}

    public function updatedStartsAt(): void
    {
        if ($this->subscription_plan_id) {
            $this->updatedSubscriptionPlanId($this->subscription_plan_id);
        }
    }

    public function edit($id): void
    {
        $subscription = SchoolSubscription::findOrFail($id);

        $this->subscription_id = $subscription->id;
        $this->school_id = $subscription->school_id;
        $this->subscription_plan_id = $subscription->subscription_plan_id;
        $this->starts_at = $subscription->starts_at?->format('Y-m-d');
        $this->expires_at = $subscription->expires_at?->format('Y-m-d');
        $this->grace_period_days = $subscription->grace_period_days;
        $this->status = $subscription->status;
        $this->amount_paid = $subscription->amount_paid;
        $this->payment_reference = $subscription->payment_reference;
        $this->paid_at = $subscription->paid_at?->format('Y-m-d');

        $this->modalOpen = true;
    }

    public function save(SchoolSubscriptionService $service): void
{
    $this->validate();

    $service->createOrUpdate($this->subscription_id, [
        'school_id' => $this->school_id,
        'subscription_plan_id' => $this->subscription_plan_id,
        'starts_at' => $this->starts_at,
        'expires_at' => $this->expires_at,
        'grace_period_days' => $this->grace_period_days,
        'status' => $this->status,
        'amount_paid' => $this->amount_paid ?? 0,
        'payment_reference' => $this->payment_reference,
        'paid_at' => $this->paid_at,
    ]);

    session()->flash('success', 'School subscription saved successfully.');

    $this->modalOpen = false;
    $this->resetForm();
}

    public function cancel($id): void
    {
        SchoolSubscription::findOrFail($id)->update([
            'status' => 'cancelled',
        ]);

        session()->flash('success', 'Subscription cancelled.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'subscription_id',
            'school_id',
            'subscription_plan_id',
            'starts_at',
            'expires_at',
            'grace_period_days',
            'status',
            'amount_paid',
            'payment_reference',
            'paid_at',
        ]);

        $this->grace_period_days = 7;
        $this->status = 'active';
        $this->amount_paid = 0;
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.schools.index', [
            'subscriptions' => SchoolSubscription::with(['school', 'plan', 'creator'])
                ->whereHas('school', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('plan', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),

            'schools' => School::orderBy('name')->get(),

            'plans' => SubscriptionPlan::where('type', 'school')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app');
    }
}
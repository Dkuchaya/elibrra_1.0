<?php

namespace App\Livewire\Admin\Subscriptions\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Services\UserSubscriptionService;

class Index extends Component
{
    use WithPagination;
    public $subscription_id;

public $user_id;
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
        'user_id' => 'required|exists:users,id',

        'subscription_plan_id'
            => 'required|exists:subscription_plans,id',

        'starts_at' => 'required|date',

        'expires_at'
            => 'required|date|after_or_equal:starts_at',

        'grace_period_days'
            => 'required|integer|min:0',

        'status'
            => 'required|in:active,expired,cancelled',

        'amount_paid'
            => 'nullable|numeric|min:0',

        'payment_reference'
            => 'nullable|string|max:255',

        'paid_at'
            => 'nullable|date',
    ];
}

public function openModal(): void
{
    $this->resetForm();

    $this->starts_at = now()->format('Y-m-d');
    $this->paid_at = now()->format('Y-m-d');

    $this->modalOpen = true;
}

public function resetForm(): void
{
    $this->reset([
        'subscription_id',
        'user_id',
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

public function updatedSubscriptionPlanId($value): void
{
    if (!$value) {
        return;
    }

    $service = app(UserSubscriptionService::class);

    $this->starts_at =
        $this->starts_at ?: now()->format('Y-m-d');

    $data = $service->calculateDates(
        $value,
        $this->starts_at
    );

    $this->expires_at = $data['expires_at'];

    $this->grace_period_days =
        $data['grace_period_days'];

    $this->amount_paid =
        $data['amount_paid'];
}

public function save(): void
{
    $this->validate();

    $service = app(UserSubscriptionService::class);

    $service->createOrUpdate(
        $this->subscription_id,
        [
            'user_id' => $this->user_id,

            'subscription_plan_id'
                => $this->subscription_plan_id,

            'starts_at' => $this->starts_at,

            'expires_at' => $this->expires_at,

            'grace_period_days'
                => $this->grace_period_days,

            'status' => $this->status,

            'amount_paid'
                => $this->amount_paid,

            'payment_reference'
                => $this->payment_reference,

            'paid_at'
                => $this->paid_at,
        ]
    );

    session()->flash(
        'success',
        'User subscription saved successfully.'
    );

    $this->modalOpen = false;

    $this->resetForm();
}

    public function render()
{
    return view(
        'livewire.admin.subscriptions.users.index',
        [
            'subscriptions' =>
                UserSubscription::with([
                    'user',
                    'plan'
                ])
                ->latest()
                ->paginate(10),

            'plans' =>
                SubscriptionPlan::where(
                    'type',
                    'individual'
                )
                ->where('is_active', true)
                ->get(),

            'users' =>
                User::orderBy('name')->get(),
        ]
    )->layout('layouts.app');
}
}

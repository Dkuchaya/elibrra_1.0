<?php

namespace App\Services;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionService
{
    public function calculateDates(int $planId, string $startsAt): array
    {
        $plan = SubscriptionPlan::findOrFail($planId);

        return [
            'starts_at' => $startsAt,

            'expires_at' => Carbon::parse($startsAt)
                ->addDays($plan->duration_days)
                ->format('Y-m-d'),

            'grace_period_days' => $plan->grace_period_days,

            'amount_paid' => $plan->price,
        ];
    }

    public function createOrUpdate(?int $id, array $data): UserSubscription
    {
        return UserSubscription::updateOrCreate(
            ['id' => $id],
            $data
        );
    }

    public function cancel(UserSubscription $subscription): void
    {
        $subscription->update([
            'status' => 'cancelled',
        ]);
    }

    public function isActive(UserSubscription $subscription): bool
    {
        return $subscription->status === 'active'
            && now()->lte(
                $subscription->expires_at->copy()
                    ->addDays($subscription->grace_period_days)
            );
    }
}
<?php

namespace App\Services;

use App\Models\SchoolSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SchoolSubscriptionService
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

    public function createOrUpdate(?int $id, array $data): SchoolSubscription
    {
        $data['created_by'] = $data['created_by'] ?? Auth::id();

        return SchoolSubscription::updateOrCreate(
            ['id' => $id],
            $data
        );
    }

    public function cancel(SchoolSubscription $subscription): void
    {
        $subscription->update([
            'status' => 'cancelled',
        ]);
    }

    public function isActive(SchoolSubscription $subscription): bool
    {
        return $subscription->status === 'active'
            && now()->lte(
                $subscription->expires_at->copy()->addDays($subscription->grace_period_days)
            );
    }
}
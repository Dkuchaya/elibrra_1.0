<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSubscription extends Model
{
    protected $fillable = [
        'school_id',
        'subscription_plan_id',
        'starts_at',
        'expires_at',
        'grace_period_days',
        'status',
        'created_by',
        'amount_paid',
        'payment_reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'expires_at' => 'date',
            'paid_at' => 'date',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSubscription extends Model
{
    protected $fillable = ['school_id','subscription_plan_id','starts_at','expires_at','grace_period_days','status','created_by'];

    protected function casts(): array
    {
        return ['starts_at' => 'date', 'expires_at' => 'date'];
    }

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function plan(): BelongsTo { return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'subscription_start',
        'subscription_end',
        'is_active',

    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SchoolSubscription::class);
    }

    public function schoolSubscriptions()
{
    return $this->hasMany(\App\Models\SchoolSubscription::class);
}

 

        public function activeSubscription()
        {
            return $this->hasOne(SchoolSubscription::class)
                ->where('status', 'active')
                ->latestOfMany();
        }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'legacy_user_id','school_id','first_name','last_name','name','email','phone','gender','city','password','must_change_password','is_active','email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function userSubscriptions()
    {
        return $this->hasMany(\App\Models\UserSubscription::class);
    }

    public function readingHistories()
    {
        return $this->hasMany(\App\Models\ReadingHistory::class);
    }
    public function bookLists()
    {
        return $this->hasMany(\App\Models\BookList::class);
    }



    
}

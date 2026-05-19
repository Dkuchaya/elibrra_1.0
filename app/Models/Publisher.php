<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    protected $fillable = ['legacy_publisher_id','name','email','phone'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}

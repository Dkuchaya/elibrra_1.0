<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'legacy_publisher_id',
    'name',
    'slug',
    'email',
    'phone',
    'address',
    'is_active',
];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

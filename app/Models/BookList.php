<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookList extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'visibility',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_list_items')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('book_list_items.sort_order');
    }

    public function isPublic(): bool
    {
        return in_array($this->visibility, ['public', 'unlisted']);
    }
}
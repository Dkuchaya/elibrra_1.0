<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'legacy_isbn',
        'publisher_id',
        'book_category_id',
        'isbn',
        'title',
        'slug',
        'description',
        'cover_image',
        'pdf_path',
        'pages',
        'published_year',
        'language',
        'file_size',
        'views',
        'featured',
        'edition',
        'book_type',
        'subscription_required',
        'visibility',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'subscription_required' => 'boolean',
            'views' => 'integer',
            'file_size' => 'integer',
        ];
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book')
            ->withTimestamps();
    }
}
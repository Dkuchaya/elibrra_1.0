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
        'legacy_isbn','school_id','publisher_id','book_category_id','isbn','title','description','cover_image','pdf_path','pages','published_year','visibility','is_active'
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function publisher(): BelongsTo { return $this->belongsTo(Publisher::class); }
    public function category(): BelongsTo { return $this->belongsTo(BookCategory::class, 'book_category_id'); }
    public function authors(): BelongsToMany { return $this->belongsToMany(Author::class)->withTimestamps(); }
}

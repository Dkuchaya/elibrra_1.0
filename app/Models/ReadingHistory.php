<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingHistory extends Model
{
    protected $fillable = ['user_id','book_id','last_page','last_read_at'];

    protected function casts(): array
    {
        return ['last_read_at' => 'datetime'];
    }
}

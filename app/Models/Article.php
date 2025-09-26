<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    protected $fillable = [
        'external_id','source_code','title','author','url',
        'summary','content','image','category','published_at','raw'
    ];
    protected $casts = [
        'published_at' => 'datetime',
        'raw' => 'array',
    ];
}


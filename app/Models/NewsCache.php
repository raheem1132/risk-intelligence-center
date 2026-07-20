<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    protected $table = 'news_cache';

    protected $fillable = [
        'country_code',
        'title',
        'description',
        'url',
        'source_name',
        'sentiment_status'
    ];
}
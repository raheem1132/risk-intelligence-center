<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentDictionary extends Model
{
    // Menentukan nama tabel secara manual karena bentuk jamaknya tidak standar bahasa Inggris
    protected $table = 'sentiment_dictionaries';

    protected $fillable = [
        'word',
        'type'
    ];
}
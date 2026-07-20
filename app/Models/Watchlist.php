<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    protected $fillable = [
        'user_id',
        'country_id'
    ];

    /**
     * Relasi balik ke data User yang memantau
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke data Negara yang dipantau
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
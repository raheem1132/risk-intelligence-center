<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'port_name',
        'port_code',
        'latitude',
        'longitude'
    ];

    /**
     * Relasi balik ke tabel countries
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
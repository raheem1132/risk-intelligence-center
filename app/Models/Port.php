<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'port_name',
        'country_code',
        'country_name',
        'port_code',
        'latitude',
        'longitude',
        'risk_status',
        'risk_score',
        'details',
        'wpi_number',
        'harbor_size',
        'harbor_type',
        'source',
    ];

    /**
     * Relasi balik ke tabel countries
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}

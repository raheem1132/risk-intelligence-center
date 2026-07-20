<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskScore extends Model
{
    protected $fillable = [
        'country_id',
        'weather_risk',
        'inflation_risk',
        'currency_risk',
        'news_risk',
        'total_score',
        'status'
    ];

    /**
     * Relasi balik ke tabel countries
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
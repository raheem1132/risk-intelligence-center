<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code_iso2',
        'region',
        'currency_code',
        'language',
        'population',
        'gdp',
        'inflation_rate',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke tabel ports (Satu negara bisa punya banyak pelabuhan)
     */
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    /**
     * Relasi ke tabel risk_scores (Satu negara punya riwayat skor risiko)
     */
    public function riskScores(): HasMany
    {
        return $this->hasMany(RiskScore::class);
    }

    public function economicIndicators(): HasMany { return $this->hasMany(EconomicIndicator::class); }
    public function weatherSnapshots(): HasMany { return $this->hasMany(WeatherSnapshot::class); }
    public function currencySnapshots(): HasMany { return $this->hasMany(CurrencySnapshot::class); }
}

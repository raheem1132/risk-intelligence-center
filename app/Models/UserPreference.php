<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id', 'risk_threshold', 'refresh_interval', 'timezone',
        'base_currency', 'density', 'email_alerts', 'browser_alerts', 'weekly_digest',
    ];

    protected function casts(): array
    {
        return [
            'email_alerts' => 'boolean',
            'browser_alerts' => 'boolean',
            'weekly_digest' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

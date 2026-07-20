<?php

namespace App\Services;

use App\Models\Country;

class RiskScoringService
{
    /**
     * Menghitung rincian risiko dan status untuk satu negara.
     */
    public function calculateRisk(Country $country): array
    {
        // Deterministic seeding agar skor konsisten per ID negara
        mt_srand($country->id);
        
        $weatherRisk       = mt_rand(10, 80);
        $inflationRisk     = mt_rand(15, 90);
        $newsSentimentRisk = mt_rand(5, 75);
        $currencyRisk      = mt_rand(20, 60);

        // Rumus pembobotan profesional (Weighting Matrix)
        $totalRiskScore = round(
            ($weatherRisk * 0.30) + 
            ($inflationRisk * 0.20) + 
            ($newsSentimentRisk * 0.40) + 
            ($currencyRisk * 0.10)
        );

        // Klasifikasi Tingkat Risiko (Thresholding)
        if ($totalRiskScore < 35) {
            $status = 'Low Risk';
            $badgeColor = 'bg-green-900/50 text-green-400 border-green-700';
        } elseif ($totalRiskScore <= 65) {
            $status = 'Medium Risk';
            $badgeColor = 'bg-yellow-900/50 text-yellow-400 border-yellow-700';
        } else {
            $status = 'High Risk';
            $badgeColor = 'bg-red-900/50 text-red-400 border-red-700';
        }

        return [
            'country_id'       => $country->id,
            'country_name'     => $country->name,
            'code_iso2'        => $country->code_iso2,
            'breakdown'        => [
                'weather_risk'        => $weatherRisk,
                'inflation_risk'      => $inflationRisk,
                'news_sentiment_risk' => $newsSentimentRisk,
                'currency_risk'       => $currencyRisk,
            ],
            'total_risk_score' => $totalRiskScore,
            'status'           => $status,
            'badge_class'      => $badgeColor
        ];
    }

    /**
     * Memproses koleksi data banyak negara sekaligus.
     */
    public function getRiskDataCollection($countries): array
    {
        $collection = [];
        foreach ($countries as $country) {
            $collection[] = $this->calculateRisk($country);
        }
        return $collection;
    }
}
<?php

namespace App\Services;

use App\Models\Country;
use App\Services\NewsService;
use Illuminate\Support\Facades\Http;
use Exception;

class RiskScoringEngine
{
    protected $news;

    public function __construct(NewsService $news)
    {
        $this->news = $news;
    }

    public function calculateCountryRisk($countryId)
    {
        $country = Country::find($countryId);
        
        if (!$country) {
            return ['status' => 'error', 'message' => 'Negara tidak ditemukan'];
        }

        // Ambil data sub-skor
        $weatherRisk = $this->calcWeather($country->latitude, $country->longitude);
        $inflationRisk = $this->calcInflation($country->inflation_rate);
        $currencyRisk = $this->calcCurrency($country->currency_code);
        
        $articles = $this->news->fetchAndAnalyzeNews($country->code_iso2, 'logistics');
        $newsRisk = $this->calcNews($articles);

        // Hitung total pakai bobot (30%, 20%, 10%, 40%)
        $total = ($weatherRisk * 0.3) + ($inflationRisk * 0.2) + ($currencyRisk * 0.1) + ($newsRisk * 0.4);
        $total = round($total, 1);

        // Tentukan level risiko
        $status = 'Low Risk';
        if ($total > 65) {
            $status = 'High Risk';
        } elseif ($total >= 30) {
            $status = 'Medium Risk';
        }

        return [
            'country_name' => $country->name,
            'iso2' => $country->code_iso2,
            'total_risk_score' => $total,
            'status' => $status,
            'breakdown' => [
                'weather' => $weatherRisk,
                'inflation' => $inflationRisk,
                'currency' => $currencyRisk,
                'news' => $newsRisk
            ]
        ];
    }

    private function calcWeather($lat, $lng)
    {
        try {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current_weather=true";
            $res = Http::get($url);

            if ($res->successful()) {
                $speed = $res->json()['current_weather']['windspeed'] ?? 0;
                if ($speed > 40) return 90;
                if ($speed > 25) return 60;
                if ($speed > 15) return 30;
                return 10;
            }
        } catch (Exception $e) {}
        
        return 25;
    }

    private function calcInflation($rate)
    {
        $rate = $rate ?? 3.0;
        if ($rate > 15) return 95;
        if ($rate > 8) return 70;
        if ($rate > 4) return 45;
        if ($rate < 0) return 60;
        return 15;
    }

    private function calcCurrency($code)
    {
        if ($code === 'USD') return 5;

        try {
            $res = Http::get("https://open.er-api.com/v6/latest/USD");
            if ($res->successful()) {
                $rates = $res->json()['rates'] ?? [];
                $value = $rates[$code] ?? null;

                if ($value) {
                    if ($value > 10000) return 65;
                    if ($value > 100) return 40;
                    return 20;
                }
            }
        } catch (Exception $e) {}

        return 30;
    }

    private function calcNews($articles)
    {
        if (isset($articles['status']) && $articles['status'] === 'error') {
            return 40;
        }

        if (empty($articles) || !is_array($articles)) {
            return 20;
        }

        $totalNeg = 0;
        foreach ($articles as $item) {
            $totalNeg += $item['sentiment']['negative_pct'] ?? 0;
        }

        return round($totalNeg / count($articles));
    }
}
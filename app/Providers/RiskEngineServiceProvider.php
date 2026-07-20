<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\WordDictionary;
use App\Models\RiskScore;

class RiskEngineServiceProvider extends ServiceProvider
{
    /**
     * Jalankan kalkulasi risiko supply chain berdasarkan parameter input.
     * Semua parameter bernilai 0 - 100.
     */
    public static function calculateRisk($countryId, $weatherCondition, $inflationRate, $currencyVolatility, $newsText)
    {
        // 1. Weather Risk (0 - 100): Kondisi ekstrem memicu skor tinggi
        // Jika badai/storm/extreme skor otomatis tinggi, jika clear skor rendah
        $weatherRisk = 20.0;
        $weatherLower = strtolower($weatherCondition);
        if (str_contains($weatherLower, 'storm') || str_contains($weatherLower, 'rain') || str_contains($weatherLower, 'flood')) {
            $weatherRisk = 85.0;
        } elseif (str_contains($weatherLower, 'cloud') || str_contains($weatherLower, 'overcast')) {
            $weatherRisk = 50.0;
        }

        // 2. Inflation Risk (0 - 100): Menghitung tingkat inflasi negara
        // Inflasi normal di kisaran 2-4%. Di atas 5% risiko mulai naik linear
        $inflationRisk = min(100, max(0, $inflationRate * 5)); 

        // 3. Currency Risk (0 - 100): Volatilitas mata uang dalam persen
        $currencyRisk = min(100, max(0, $currencyVolatility * 10));

        // 4. News Sentiment Risk (0 - 100): Scanning kamus kata di berita
        $words = str_word_count(strtolower($newsText), 1);
        $positiveWords = WordDictionary::where('type', 'positive')->pluck('word')->toArray();
        $negativeWords = WordDictionary::where('type', 'negative')->pluck('word')->toArray();

        $posCount = 0;
        $negCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) $posCount++;
            if (in_array($word, $negativeWords)) $negCount++;
        }

        // Rumus sentimen dasar: jika banyak kata negatif, risiko mendekati 100
        $totalWords = $posCount + $negCount;
        if ($totalWords > 0) {
            $newsRisk = ($negCount / $totalWords) * 100;
        } else {
            $newsRisk = 50.0; // Netral jika tidak ada kata yang cocok
        }

        // 5. Total Risk Score: Rata-rata bobot dari ke-4 komponen risiko
        $totalRiskScore = ($weatherRisk + $inflationRisk + $currencyRisk + $newsRisk) / 4;

        // Tentukan tingkatan Risk Level berdasarkan bobot total
        if ($totalRiskScore >= 70) {
            $riskLevel = 'High';
        } elseif ($totalRiskScore >= 40) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }

        // 6. Simpan hasil kalkulasi ke database agar bisa dipanggil oleh Chart/Map dashboard
        return RiskScore::updateOrCreate(
            ['country_id' => $countryId],
            [
                'weather_risk' => $weatherRisk,
                'inflation_risk' => $inflationRisk,
                'currency_risk' => $currencyRisk,
                'news_risk' => $newsRisk,
                'total_risk_score' => $totalRiskScore,
                'risk_level' => $riskLevel
            ]
        );
    }

    public function register(): void {}
    public function boot(): void {}
}
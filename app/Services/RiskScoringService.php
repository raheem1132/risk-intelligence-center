<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;

class RiskScoringService
{
    public function __construct(private readonly GlobalDataService $data, private readonly NewsService $news) {}

    public function calculateRisk(Country $country, bool $refresh = false): array
    {
        if ($refresh) $this->data->syncCountry($country);
        $weather = $country->weatherSnapshots()->latest('observed_at')->first();
        $currency = $country->currencySnapshots()->latest('observed_at')->first();
        $weatherRisk = (int)($weather?->risk_score ?? 25);
        $inflation = (float)($country->inflation_rate ?? 3);
        $inflationRisk = (int)min(100, max(0, abs($inflation) * 6));
        $currencyRisk = (int)min(100, abs((float)($currency?->change_percent ?? 0)) * 15);
        $articles = $this->news->fetchAndAnalyzeNews($country->code_iso2, 'logistics trade shipping economy');
        $negative = collect($articles)->avg(fn ($article) => $article['sentiment']['negative_pct'] ?? 0);
        $newsRisk = (int)round($negative ?? 20);
        $total = (int)round($weatherRisk*.30 + $inflationRisk*.20 + $newsRisk*.40 + $currencyRisk*.10);
        $status = $total < 35 ? 'Low Risk' : ($total <= 65 ? 'Medium Risk' : 'High Risk');
        $score = RiskScore::create(['country_id'=>$country->id,'weather_risk'=>$weatherRisk,'inflation_risk'=>$inflationRisk,'currency_risk'=>$currencyRisk,'news_risk'=>$newsRisk,'total_score'=>$total,'status'=>$status]);
        return ['country_id'=>$country->id,'country_name'=>$country->name,'code_iso2'=>$country->code_iso2,'breakdown'=>['weather_risk'=>$weatherRisk,'inflation_risk'=>$inflationRisk,'news_sentiment_risk'=>$newsRisk,'currency_risk'=>$currencyRisk],'weights'=>['weather'=>30,'inflation'=>20,'news'=>40,'currency'=>10],'total_risk_score'=>$total,'status'=>$status,'calculated_at'=>$score->created_at->toIso8601String()];
    }

    public function getRiskDataCollection($countries, bool $refresh = false): array
    {
        return $countries->map(fn (Country $country) => $this->calculateRisk($country, $refresh))->all();
    }
}

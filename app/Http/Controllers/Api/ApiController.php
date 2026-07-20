<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Port;
use App\Services\NewsService;
use App\Services\RiskScoringEngine;
use Illuminate\Support\Facades\Http;
use Exception;

class ApiController extends Controller
{
    protected $news;
    protected $risk;

    public function __construct(NewsService $news, RiskScoringEngine $risk)
    {
        $this->news = $news;
        $this->risk = $risk;
    }

    // 1. GET /api/countries
    public function getCountries()
    {
        return response()->json(Country::all());
    }

    // 2. GET /api/risk
    public function getRisk(Request $request)
    {
        $countryId = $request->query('country_id');
        if (!$countryId) {
            return response()->json(['error' => 'ID negara kosong'], 400);
        }

        $result = $this->risk->calculateCountryRisk($countryId);
        return response()->json($result);
    }

    // 3. GET /api/ports
    public function getPorts(Request $request)
    {
        $countryId = $request->query('country_id');
        $query = Port::query();
        
        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        return response()->json($query->limit(10)->get());
    }

    // 4. GET /api/news
    public function getNews(Request $request)
    {
        $iso2 = $request->query('iso2');
        if (!$iso2) {
            return response()->json(['error' => 'Parameter ISO2 wajib diisi'], 400);
        }

        $newsData = $this->news->fetchAndAnalyzeNews($iso2, 'logistics');
        return response()->json($newsData);
    }

    // 5. GET /api/currency
    public function getCurrency()
    {
        try {
            $res = Http::get("https://open.er-api.com/v6/latest/USD");
            if ($res->successful()) {
                return response()->json($res->json());
            }
        } catch (Exception $e) {}

        return response()->json(['error' => 'Gagal mengambil data kurs'], 500);
    }

    // 6. GET /api/analytics/trend
    public function getTrendData(Request $request)
    {
        $countryId = $request->query('country_id');
        if (!$countryId) {
            return response()->json(['error' => 'Country ID wajib diisi'], 400);
        }

        $country = Country::find($countryId);
        if (!$country) {
            return response()->json(['error' => 'Negara tidak ditemukan'], 404);
        }

        $baseInflation = $country->inflation_rate ?? 3.0;
        $riskData = $this->risk->calculateCountryRisk($countryId);
        $baseRisk = $riskData['total_risk_score'] ?? 40;

        return response()->json([
            'labels' => ['2022', '2023', '2024', '2025', '2026'],
            'inflation_trend' => [$baseInflation * 0.8, $baseInflation * 1.1, $baseInflation * 0.9, $baseInflation * 1.05, $baseInflation],
            'risk_trend' => [max(10, $baseRisk - 15), max(10, $baseRisk - 5), min(100, $baseRisk + 10), max(10, $baseRisk - 2), $baseRisk]
        ]);
    }

    // 7. GET /api/countries/compare
    public function compareCountries(Request $request)
    {
        $c1 = $request->query('country1');
        $c2 = $request->query('country2');

        if (!$c1 || !$c2) {
            return response()->json(['error' => 'Pilih dua negara terlebih dahulu'], 400);
        }

        return response()->json([
            'country1' => $this->risk->calculateCountryRisk($c1),
            'country2' => $this->risk->calculateCountryRisk($c2)
        ]);
    }
}
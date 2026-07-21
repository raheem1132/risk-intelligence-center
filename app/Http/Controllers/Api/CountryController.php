<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        // Mengambil semua data negara dari database
        $countries = Country::all();

        // Mengembalikan data dalam bentuk JSON sesuai standar REST API tugas
        return response()->json([
            'status' => 'success',
            'data' => $countries
        ], 200);
    }

    public function show(string $code)
    {
        $country = Country::where('code_iso2', strtoupper($code))->first();

        if (! $country) {
            return response()->json(['status' => 'error', 'message' => 'Country not found.'], 404);
        }

        $economy = $country->economicIndicators()->latest('year')->first();
        $risk = $country->riskScores()->latest()->first();
        return response()->json(['status' => 'success', 'data' => array_merge($country->toArray(), [
            'gdp' => $economy?->gdp ?? $country->gdp,
            'population' => $economy?->population ?? $country->population,
            'inflation_rate' => $economy?->inflation ?? $country->inflation_rate,
            'economic_year' => $economy?->year,
            'risk' => $risk ? [
                'total_score'=>(float)$risk->total_score,'status'=>$risk->status,
                'weather_risk'=>(float)$risk->weather_risk,'inflation_risk'=>(float)$risk->inflation_risk,
                'currency_risk'=>(float)$risk->currency_risk,'news_risk'=>(float)$risk->news_risk,
                'calculated_at'=>$risk->created_at?->toIso8601String(),
            ] : null,
        ])]);
    }
}

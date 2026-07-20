<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\RiskScore;
use App\Providers\RiskEngineServiceProvider;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard (Web View)
     */
    public function index()
    {
        $countries = Country::with('riskScore')->get();
        return view('dashboard', compact('countries'));
    }

    /**
     * API Endpoint untuk memproses data dari form dan hitung skor risiko baru
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'weather_condition' => 'required|string',
            'inflation_rate' => 'required|numeric',
            'currency_volatility' => 'required|numeric',
            'news_text' => 'required|string',
        ]);

        // Panggil engine kalkulasi yang sudah kita buat sebelumnya
        $riskData = RiskEngineServiceProvider::calculateRisk(
            $request->country_id,
            $request->weather_condition,
            $request->inflation_rate,
            $request->currency_volatility,
            $request->news_text
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Kalkulasi risiko berhasil diperbarui!',
            'data' => $riskData->load('country')
        ]);
    }
}
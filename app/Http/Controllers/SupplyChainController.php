<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\Watchlist;
use App\Services\SentimentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyChainController extends Controller
{
    protected $sentimentService;

    public function __construct(SentimentService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
    }

    // Tampilan Dashboard Utama & Handler AJAX
    public function index(Request $request)
    {
        // Jalur AJAX 1: Ambil detail skor & pelabuhan untuk peta saat negara dipilih
        if ($request->ajax() && $request->has('country_id') && !$request->has('trend')) {
            $countryId = $request->query('country_id');
            $country = Country::with(['ports'])->find($countryId);

            if (!$country) return response()->json(['error' => 'Not found'], 404);
            $latestScore = RiskScore::where('country_id', $countryId)->latest()->first();

            return response()->json([
                'country_name' => $country->name,
                'total_risk_score' => $latestScore ? $latestScore->total_score : 0,
                'status' => $latestScore ? $latestScore->status : 'Belum Dihitung',
                'breakdown' => [
                    'weather' => $latestScore ? $latestScore->weather_risk : 0,
                    'inflation' => $latestScore ? $latestScore->inflation_risk : 0,
                    'currency' => $latestScore ? $latestScore->currency_risk : 0,
                    'news' => $latestScore ? $latestScore->news_risk : 0,
                ],
                'ports' => $country->ports
            ]);
        }

        // Jalur AJAX 2: Ambil data tren historis pengujian untuk grafik Chart.js
        if ($request->ajax() && $request->has('country_id') && $request->has('trend')) {
            $scores = RiskScore::where('country_id', $request->query('country_id'))
                                ->latest()->limit(5)->get()->reverse();
            $labels = []; $riskTrend = []; $inflationTrend = []; $i = 1;
            foreach ($scores as $score) {
                $labels[] = 'Uji #' . $i++;
                $riskTrend[] = $score->total_score;
                $inflationTrend[] = $score->inflation_risk;
            }
            if (count($labels) === 0) {
                $labels = ['Mulai']; $riskTrend = [0]; $inflationTrend = [0];
            }
            return response()->json(['labels' => $labels, 'risk_trend' => $riskTrend, 'inflation_trend' => $inflationTrend]);
        }

        // Jalur AJAX 3: Membandingkan 2 negara secara side-by-side
        if ($request->ajax() && $request->has('compare1') && $request->has('compare2')) {
            $c1 = RiskScore::where('country_id', $request->query('compare1'))->latest()->first();
            $c2 = RiskScore::where('country_id', $request->query('compare2'))->latest()->first();
            return response()->json([
                'country1' => [
                    'name' => Country::find($request->query('compare1'))->name ?? 'Negara A',
                    'total_score' => $c1->total_score ?? 0,
                    'status' => $c1->status ?? 'Belum Uji',
                    'weather' => $c1->weather_risk ?? 0, 'inflation' => $c1->inflation_risk ?? 0,
                    'currency' => $c1->currency_risk ?? 0, 'news' => $c1->news_risk ?? 0,
                ],
                'country2' => [
                    'name' => Country::find($request->query('compare2'))->name ?? 'Negara B',
                    'total_score' => $c2->total_score ?? 0,
                    'status' => $c2->status ?? 'Belum Uji',
                    'weather' => $c2->weather_risk ?? 0, 'inflation' => $c2->inflation_risk ?? 0,
                    'currency' => $c2->currency_risk ?? 0, 'news' => $c2->news_risk ?? 0,
                ]
            ]);
        }

        // Tampilan Standar saat pertama dimuat
        $countries = Country::with(['ports', 'riskScores' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        $watchlists = Auth::check() 
            ? Watchlist::where('user_id', Auth::id())->pluck('country_id')->toArray() 
            : [];

        return view('dashboard', compact('countries', 'watchlists'));
    }

    // Aksi hitung risiko otomatis bawaan abang
    public function calculateRisk(Request $request, $countryId)
    {
        $country = Country::findOrFail($countryId);
        $weatherRisk = rand(20, 90);
        $currencyRisk = rand(10, 85);
        $inflationRisk = min(100, max(0, intval($country->inflation_rate * 10)));

        $mockNews = [
            "Economic crisis and inflation delay port supply chain infrastructure",
            "Stable growth and increase in cargo traffic improve market conditions",
            "War and natural disaster causes severe port delay and logistics crisis"
        ];
        $newsText = $mockNews[array_rand($mockNews)];
        
        $sentiment = $this->sentimentService->analyze($newsText);
        $newsRisk = $sentiment === 'Negative' ? 85 : ($sentiment === 'Positive' ? 25 : 50);

        $totalScore = intval(($weatherRisk + $inflationRisk + $currencyRisk + $newsRisk) / 4);
        $status = $totalScore >= 70 ? 'High Risk' : ($totalScore >= 40 ? 'Medium Risk' : 'Low Risk');

        RiskScore::create([
            'country_id' => $country->id,
            'weather_risk' => $weatherRisk,
            'inflation_risk' => $inflationRisk,
            'currency_risk' => $currencyRisk,
            'news_risk' => $newsRisk,
            'total_score' => $totalScore,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Skor risiko supply chain berhasil diperbarui untuk ' . $country->name);
    }

    // Aksi watchlist bawaan abang
    public function toggleWatchlist($countryId)
    {
        $userId = Auth::id() ?? 1;
        $exists = Watchlist::where('user_id', $userId)->where('country_id', $countryId)->first();
        if ($exists) {
            $exists->delete();
            $msg = 'Negara dihapus dari daftar pantau.';
        } else {
            Watchlist::create(['user_id' => $userId, 'country_id' => $countryId]);
            $msg = 'Negara berhasil dipantau!';
        }
        return redirect()->back()->with('success', $msg);
    }
}
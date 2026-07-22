<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\GlobalDataService;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    private function country(string $code): Country { return Country::where('code_iso2', strtoupper($code))->firstOrFail(); }
    public function sync(string $code, GlobalDataService $service): JsonResponse { return response()->json(['status'=>'success','data'=>$service->syncCountry($this->country($code))]); }
    public function economy(string $code): JsonResponse { $c=$this->country($code); return response()->json(['status'=>'success','data'=>$c->economicIndicators()->orderBy('year')->get()]); }
    public function refreshEconomy(string $code, GlobalDataService $service): JsonResponse
    {
        $country = $this->country($code);
        $data = $service->economy($country);

        return response()->json([
            'status' => 'success',
            'message' => 'Data ekonomi terbaru berhasil diambil dari World Bank.',
            'updated_at' => now()->toIso8601String(),
            'data' => $data,
        ]);
    }
    public function weather(string $code): JsonResponse { $c=$this->country($code); return response()->json(['status'=>'success','data'=>$c->weatherSnapshots()->latest('observed_at')->limit(30)->get()]); }
    public function currency(string $code): JsonResponse { $c=$this->country($code); return response()->json(['status'=>'success','data'=>$c->currencySnapshots()->latest('observed_at')->limit(30)->get()]); }
    public function riskTrend(string $code): JsonResponse { $c=$this->country($code); return response()->json(['status'=>'success','data'=>$c->riskScores()->latest()->limit(30)->get()->reverse()->values()]); }
    public function overview(): JsonResponse { return response()->json(['status'=>'success','data'=>['countries'=>Country::count(),'ports'=>\App\Models\Port::count(),'risk_snapshots'=>\App\Models\RiskScore::count(),'high_risk'=>\App\Models\RiskScore::where('status','High Risk')->count()]]); }
}

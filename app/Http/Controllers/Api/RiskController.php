<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\RiskScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    protected RiskScoringService $riskService;

    // Menghubungkan langsung ke folder Services yang kamu buat tadi lek
    public function __construct(RiskScoringService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('country');
        
        // Optimasi query database agar tarikan data enteng
        $query = Country::select(['id', 'name', 'code_iso2']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('code_iso2', strtoupper($search))
                  ->orWhere('name', 'like', '%' . $search . '%');
            });
            $countries = $query->take(10)->get();
        } else {
            // Ambil maksimal 50 data negara teratas
            $countries = $query->take(50)->get();
        }

        // Lempar data ke Service untuk dihitung rumusnya secara otomatis
        $processedData = $this->riskService->getRiskDataCollection($countries);

        return response()->json([
            'status' => 'success',
            'data'   => $processedData
        ], 200);
    }
}
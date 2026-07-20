<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RiskIntelligenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortApiController extends Controller
{
    protected $riskService;

    // Inject Service AI yang baru kita buat tadi lek
    public function __construct(RiskIntelligenceService $riskService)
    {
        $this->riskService = $riskService;
    }

    /**
     * 1. API Ambil Semua Data Pelabuhan beserta Skor & Status Risikonya
     */
    public function index()
    {
        $ports = DB::table('ports')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Port risk intelligence data retrieved successfully.',
            'data' => $ports
        ], 200);
    }

    /**
     * 2. API Detail Pelabuhan Berdasarkan ID + Jalankan Analisis Sentimen Live
     */
    public function show($id)
    {
        $port = DB::table('ports')->where('id', $id)->first();

        if (!$port) {
            return response()->json([
                'status' => 'error',
                'message' => 'Port not found.'
            ], 404);
        }

        // Jalankan mesin analisis sentimen berdasarkan kode negara pelabuhan
        $analysis = $this->riskService->analyzeNewsSentiment($port->country_code);

        // Hitung ulang skor risiko gabungan menggunakan formula PDF
        $finalRiskScore = $this->riskService->calculatePortRisk($port->risk_score, $analysis['sentiment_score']);
        $finalRiskStatus = $this->riskService->determineRiskStatus($finalRiskScore);

        return response()->json([
            'status' => 'success',
            'port_details' => [
                'id' => $port->id,
                'port_name' => $port->port_name,
                'country' => $port->country_name,
                'country_code' => $port->country_code,
                'coordinates' => [
                    'latitude' => $port->latitude,
                    'longitude' => $port->longitude
                ],
                'base_risk_score' => $port->risk_score,
                'calculated_sentiment_score' => $analysis['sentiment_score'],
                'final_ai_risk_score' => $finalRiskScore,
                'final_ai_risk_status' => $finalRiskStatus,
                'static_description' => $port->details
            ],
            'live_news_analyzed' => $analysis['news_data']
        ], 200);
    }

    /**
     * 3. API Tambah Pelabuhan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'port_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:5',
            'country_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'risk_score' => 'required|numeric|between:0,100'
        ]);

        $status = $this->riskService->determineRiskStatus($request->risk_score);

        $id = DB::table('ports')->insertGetId([
            'port_name' => $request->port_name,
            'country_code' => $request->country_code,
            'country_name' => $request->country_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'risk_status' => $status,
            'risk_score' => $request->risk_score,
            'details' => $request->details ?? 'Operational port data registered via API.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'New port registered successfully.',
            'port_id' => $id
        ], 21);
    }

    /**
     * 4. API Update Data/Skor Pelabuhan
     */
    public function update(Request $request, $id)
    {
        $port = DB::table('ports')->where('id', $id)->first();
        if (!$port) {
            return response()->json(['status' => 'error', 'message' => 'Port not found.'], 404);
        }

        $updateData = [];
        if ($request->has('port_name')) $updateData['port_name'] = $request->port_name;
        if ($request->has('risk_score')) {
            $updateData['risk_score'] = $request->risk_score;
            $updateData['risk_status'] = $this->riskService->determineRiskStatus($request->risk_score);
        }
        if ($request->has('details')) $updateData['details'] = $request->details;

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
            DB::table('ports')->where('id', $id)->update($updateData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Port data updated successfully.'
        ], 200);
    }

    /**
     * 5. API Hapus Pelabuhan
     */
    public function destroy($id)
    {
        $deleted = DB::table('ports')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Port not found or already deleted.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Port successfully removed from tracking matrix.'
        ], 200);
    }
}
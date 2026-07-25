<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
    public function ports(Request $request): JsonResponse
    {
        $limit = min(15000, max(500, $request->integer('limit', 15000)));
        $total = Port::whereNotNull('latitude')->whereNotNull('longitude')->count();
        $ports = Port::query()
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->orderByRaw("CASE WHEN harbor_size = 'L' THEN 0 WHEN harbor_size = 'M' THEN 1 ELSE 2 END")
            ->limit($limit)
            ->get(['port_name','country_name','country_code','latitude','longitude','harbor_size','harbor_type','wpi_number','source'])
            ->map(fn (Port $port) => [
                'name'=>$port->port_name, 'country'=>$port->country_name, 'code'=>$port->country_code,
                'lat'=>(float)$port->latitude, 'lng'=>(float)$port->longitude,
                'size'=>$port->harbor_size, 'type'=>$port->harbor_type,
                'wpi'=>$port->wpi_number, 'source'=>$port->source,
            ]);

        return response()->json(['status'=>'success','data'=>$ports,'meta'=>['total'=>$total,'displayed'=>$ports->count()]])
            ->header('Cache-Control', 'public, max-age=900');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\JsonResponse;

class MapDataController extends Controller
{
    public function ports(): JsonResponse
    {
        $ports = Port::query()
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->limit(15000)
            ->get(['port_name','country_name','country_code','latitude','longitude','harbor_size','harbor_type','wpi_number','source'])
            ->map(fn (Port $port) => [
                'name'=>$port->port_name, 'country'=>$port->country_name, 'code'=>$port->country_code,
                'lat'=>(float)$port->latitude, 'lng'=>(float)$port->longitude,
                'size'=>$port->harbor_size, 'type'=>$port->harbor_type,
                'wpi'=>$port->wpi_number, 'source'=>$port->source,
            ]);

        return response()->json(['status'=>'success','data'=>$ports])
            ->header('Cache-Control', 'public, max-age=900');
    }
}

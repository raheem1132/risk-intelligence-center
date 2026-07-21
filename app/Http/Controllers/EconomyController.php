<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\View\View;

class EconomyController extends Controller
{
    public function index(): View
    {
        $records = Country::with(['economicIndicators'=>fn($query)=>$query->latest('year')->limit(1)])
            ->orderBy('name')->get()->map(function(Country $country){
                $snapshot=$country->economicIndicators->first();
                return [
                    'id'=>$country->id,'name'=>$country->name,'code'=>strtolower($country->code_iso2),
                    'region'=>$country->region ?: 'Other','gdp'=>$snapshot?->gdp ?? $country->gdp,
                    'inflation'=>$snapshot?->inflation ?? $country->inflation_rate,
                    'population'=>$snapshot?->population ?? $country->population,
                    'year'=>$snapshot?->year,'synced'=>(bool)$snapshot,
                ];
            });
        $covered=$records->where('synced',true);
        $topGdp=$records->filter(fn($r)=>$r['gdp']!==null)->sortByDesc('gdp')->take(8)->values();
        $inflationLeaders=$records->filter(fn($r)=>$r['inflation']!==null)->sortByDesc('inflation')->take(5)->values();
        $regionData=$records->groupBy('region')->map(fn($items)=>$items->where('synced',true)->count())->sortDesc();
        return view('economy',[
            'countries'=>$records,'covered'=>$covered->count(),'topGdp'=>$topGdp,
            'inflationLeaders'=>$inflationLeaders,'regionData'=>$regionData,
            'topGdpLabels'=>$topGdp->pluck('name')->values(),
            'topGdpValues'=>$topGdp->pluck('gdp')->map(fn($value)=>round($value/1e12,3))->values(),
            'totalGdp'=>$records->sum(fn($r)=>(float)($r['gdp']??0)),
            'averageInflation'=>round($records->filter(fn($r)=>$r['inflation']!==null)->avg('inflation')??0,2),
            'highInflation'=>$records->filter(fn($r)=>(float)($r['inflation']??0)>=8)->count(),
        ]);
    }
}

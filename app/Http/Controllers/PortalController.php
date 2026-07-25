<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\WeatherSnapshot;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(Request $request, NewsService $news): View
    {
        $latestIds = RiskScore::selectRaw('MAX(id) id')->groupBy('country_id');
        $scores = RiskScore::with('country')->whereIn('id',$latestIds)->get();
        $countries = Country::orderBy('name')->get(['id','name','code_iso2']);
        $selected = Country::with(['economicIndicators'=>fn($q)=>$q->orderBy('year'),'currencySnapshots'=>fn($q)=>$q->orderBy('observed_at'),'weatherSnapshots'=>fn($q)=>$q->latest('observed_at')->limit(1),'riskScores'=>fn($q)=>$q->latest()->limit(12),'ports'=>fn($q)=>$q->limit(8)])
            ->where('code_iso2',strtoupper($request->get('country','ID')))->first() ?: Country::with(['economicIndicators','currencySnapshots','weatherSnapshots','riskScores','ports'])->first();
        $articles=$news->fetchAndAnalyzeNews('GLOBAL','logistics');
        $sentiments=collect($articles)->countBy(fn($a)=>$a['sentiment']['label']??'Neutral');
        return view('dashboard', [
            'countryCount'=>Country::count(),'portCount'=>Port::count(),'scores'=>$scores,'averageRisk'=>round($scores->avg('total_score')??0,1),'highRisk'=>$scores->where('status','High Risk')->count(),
            'extremeWeather'=>WeatherSnapshot::where('risk_score','>=',65)->distinct('country_id')->count('country_id'),'articles'=>$articles,'sentiments'=>$sentiments,'countries'=>$countries,'selected'=>$selected,
        ]);
    }
    public function weather(): View { return view('weather',['ports'=>collect()]); }
    public function countries(Request $request): View {
        $query=Country::with(['economicIndicators'=>fn($q)=>$q->latest('year')->limit(1),'riskScores'=>fn($q)=>$q->latest()->limit(1)])
            ->when($request->q,fn($q,$s)=>$q->where('name','like',"%{$s}%"))
            ->when($request->region,fn($q,$s)=>$q->where('region',$s));
        return view('countries',['countries'=>$query->orderBy('name')->paginate(24)->withQueryString(),'totalCountries'=>Country::count(),'syncedCountries'=>Country::has('riskScores')->count(),'regions'=>Country::whereNotNull('region')->distinct()->orderBy('region')->pluck('region')]);
    }
    public function news(Request $request, NewsService $news): View { return view('news',['articles'=>$news->fetchAndAnalyzeNews(strtoupper($request->get('country','ID')),$request->get('keyword','logistics trade shipping economy'),$request->boolean('refresh'))]); }
    public function ports(Request $request): View
    {
        $ports=Port::with('country')->when($request->q,fn($q,$s)=>$q->where(fn($x)=>$x->where('port_name','like',"%{$s}%")->orWhere('country_name','like',"%{$s}%")->orWhere('wpi_number','like',"%{$s}%")))->orderBy('port_name')->paginate(25)->withQueryString();
        return view('ports',['ports'=>$ports,'countryCoverage'=>Port::whereNotNull('country_code')->distinct('country_code')->count('country_code'),'largePorts'=>Port::where('harbor_size','L')->count(),'locatedPorts'=>Port::whereNotNull('latitude')->whereNotNull('longitude')->count()]);
    }
    public function map(): View
    {
        $mapCountryCount = Country::whereHas('ports')->count();
        return view('map', compact('mapCountryCount'));
    }
    public function risks(): View
    {
        $countries=Country::with(['economicIndicators'=>fn($q)=>$q->orderBy('year'),'currencySnapshots'=>fn($q)=>$q->orderBy('observed_at')->limit(20),'riskScores'=>fn($q)=>$q->orderBy('created_at')->limit(20)])->orderBy('name')->get();
        return view('risk_scores',compact('countries'));
    }
    public function docs(): View { return view('api-docs'); }
}

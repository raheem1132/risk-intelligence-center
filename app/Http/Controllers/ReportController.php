<?php
namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $countries=Country::with(['economicIndicators'=>fn($q)=>$q->latest('year')->limit(1),'riskScores'=>fn($q)=>$q->latest()->limit(1)])->orderBy('name')->get();
        return view('reports.index',compact('countries'));
    }
    public function show(string $code): View
    {
        $country=$this->country($code);
        return view('reports.country',['country'=>$country,'economy'=>$country->economicIndicators->first(),'weather'=>$country->weatherSnapshots->first(),'currency'=>$country->currencySnapshots->first(),'risk'=>$country->riskScores->first()]);
    }
    public function csv(string $code): StreamedResponse
    {
        $country=$this->country($code);$economy=$country->economicIndicators->first();$weather=$country->weatherSnapshots->first();$currency=$country->currencySnapshots->first();$risk=$country->riskScores->first();
        return response()->streamDownload(function() use($country,$economy,$weather,$currency,$risk){$out=fopen('php://output','w');fputcsv($out,['Global Supply Chain Country Intelligence Report']);fputcsv($out,['Generated at',now()->toIso8601String()]);fputcsv($out,[]);fputcsv($out,['Metric','Value']);foreach([['Country',$country->name],['ISO2',$country->code_iso2],['Region',$country->region],['Currency',$country->currency_code],['Population',$economy?->population??$country->population],['GDP USD',$economy?->gdp??$country->gdp],['Inflation %',$economy?->inflation??$country->inflation_rate],['Temperature C',$weather?->temperature],['Wind km/h',$weather?->wind_speed],['Exchange rate per USD',$currency?->rate],['Risk score',$risk?->total_score],['Risk status',$risk?->status],['Weather risk',$risk?->weather_risk],['Inflation risk',$risk?->inflation_risk],['Currency risk',$risk?->currency_risk],['News risk',$risk?->news_risk]] as $row)fputcsv($out,$row);fclose($out);},strtolower($country->code_iso2).'-intelligence-report.csv',['Content-Type'=>'text/csv']);
    }
    private function country(string $code): Country
    {
        return Country::with(['economicIndicators'=>fn($q)=>$q->latest('year')->limit(1),'weatherSnapshots'=>fn($q)=>$q->latest('observed_at')->limit(1),'currencySnapshots'=>fn($q)=>$q->latest('observed_at')->limit(1),'riskScores'=>fn($q)=>$q->latest()->limit(1),'ports'=>fn($q)=>$q->limit(10)])->where('code_iso2',strtoupper($code))->firstOrFail();
    }
}

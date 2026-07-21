<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function index(): View
    {
        $market = Cache::remember('web-currency-market-usd-v2', now()->addMinutes(30), function () {
            try {
                $response = Http::withoutVerifying()->timeout(8)->retry(2, 200)->get('https://open.er-api.com/v6/latest/USD');
                return $response->successful() ? $response->json() : [];
            } catch (\Throwable) { return []; }
        });
        $rates = $market['rates'] ?? [];
        $profiles = [
            'USD'=>['US Dollar','$'],'EUR'=>['Euro','€'],'GBP'=>['British Pound','£'],'JPY'=>['Japanese Yen','¥'],'CNY'=>['Chinese Yuan','¥'],'IDR'=>['Indonesian Rupiah','Rp'],'AUD'=>['Australian Dollar','A$'],'SGD'=>['Singapore Dollar','S$'],'CAD'=>['Canadian Dollar','C$'],'CHF'=>['Swiss Franc','CHF'],'INR'=>['Indian Rupee','₹'],'KRW'=>['South Korean Won','₩'],'MYR'=>['Malaysian Ringgit','RM'],'THB'=>['Thai Baht','฿'],'NZD'=>['New Zealand Dollar','NZ$'],'AED'=>['UAE Dirham','د.إ'],'SAR'=>['Saudi Riyal','﷼'],'HKD'=>['Hong Kong Dollar','HK$'],'BRL'=>['Brazilian Real','R$'],'ZAR'=>['South African Rand','R']
        ];
        $currencies = Country::orderBy('name')->get()->map(function (Country $country) use ($rates) {
            $code = $country->currency_code ?: 'USD';
            return ['name'=>$country->name,'code'=>strtolower($country->code_iso2),'region'=>$country->region ?: 'Other','curr_code'=>$code,'rate_value'=>isset($rates[$code]) ? (float)$rates[$code] : null];
        })->map(function (array $currency) use ($profiles) {
            [$currency['curr_name'],$currency['symbol']]=$profiles[$currency['curr_code']] ?? [$currency['curr_code'].' currency',$currency['curr_code']];
            return $currency;
        })->all();
        $featured = collect(['EUR','GBP','JPY','CNY','IDR','SGD','AUD','CHF'])->map(fn($code)=>['code'=>$code,'name'=>$profiles[$code][0],'symbol'=>$profiles[$code][1],'rate'=>(float)($rates[$code]??0)])->all();
        return view('currency', ['currencies'=>$currencies,'featured'=>$featured,'updatedAt'=>$market['time_last_update_utc']??null,'nextUpdate'=>$market['time_next_update_utc']??null,'availableRates'=>count($rates)]);
    }
}

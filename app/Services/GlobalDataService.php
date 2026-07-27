<?php

namespace App\Services;

use App\Models\Country;
use App\Models\CurrencySnapshot;
use App\Models\EconomicIndicator;
use App\Models\WeatherSnapshot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use RuntimeException;

class GlobalDataService
{
    public function syncCountry(Country $country, bool $bulk = false): array
    {
        $profile = $this->profile($country);
        $economy = $this->economy($country, $bulk);
        $weather = $this->weather($country, $bulk);
        $currency = $this->currency($country);
        return compact('profile', 'economy', 'weather', 'currency');
    }

    public function profile(Country $country): array
    {
        $key = config('services.restcountries.key');
        if (!$key) {
            $port = $country->ports()->first();
            return ['latitude'=>$port?->latitude,'longitude'=>$port?->longitude];
        }
        try {
            $response = Http::withoutVerifying()->withToken($key)->timeout(10)->retry(2, 200)->get('https://api.restcountries.com/countries/v5/codes.alpha_2/'.$country->code_iso2);
            $item = $response->json('data.0.attributes') ?? $response->json('data.0') ?? $response->json('data.attributes') ?? [];
            if ($response->successful() && is_array($item)) {
                $currency = array_key_first($item['currencies'] ?? []); $coords=$item['latlng'] ?? $item['coordinates'] ?? [];
                $country->update(['name'=>$item['names']['common'] ?? $item['name']['common'] ?? $country->name,'region'=>$item['region'] ?? $country->region,'currency_code'=>$currency ?: $country->currency_code,'language'=>implode(', ', array_values($item['languages'] ?? [])) ?: $country->language,'population'=>$item['population'] ?? $country->population]);
                return ['latitude'=>$coords[0] ?? null,'longitude'=>$coords[1] ?? null];
            }
        } catch (\Throwable) {}
        $port = $country->ports()->first();
        return ['latitude'=>$port?->latitude,'longitude'=>$port?->longitude];
    }

    public function economy(Country $country, bool $bulk = false): array
    {
        $from = now()->year - 9;
        $to = now()->year;
        $indicators = ['NY.GDP.MKTP.CD'=>'gdp','FP.CPI.TOTL.ZG'=>'inflation','SP.POP.TOTL'=>'population'];
        $years = [];

        try {
            $responses = Http::pool(function (Pool $pool) use ($bulk, $country, $from, $to, $indicators) {
                $requests = [];
                foreach ($indicators as $indicator => $field) {
                    $request = $pool->as($field)
                        ->withoutVerifying()
                        ->connectTimeout(4)
                        ->timeout($bulk ? 6 : 10);

                    if (! $bulk) {
                        $request->retry(1, 200, throw: false);
                    }

                    $requests[] = $request->get(
                        "https://api.worldbank.org/v2/country/{$country->code_iso2}/indicator/{$indicator}",
                        ['format'=>'json','date'=>"{$from}:{$to}",'per_page'=>20]
                    );
                }

                return $requests;
            });
        } catch (\Throwable $exception) {
            report($exception);
            if (! $bulk) {
                throw new RuntimeException('Koneksi ke World Bank gagal. Silakan coba kembali beberapa saat lagi.', previous: $exception);
            }
            $responses = [];
        }

        $successful = 0;
        foreach ($indicators as $field) {
            $response = $responses[$field] ?? null;
            if (! $response instanceof Response || ! $response->successful()) {
                continue;
            }

            $successful++;
            foreach (($response->json()[1] ?? []) as $row) {
                if ($row['value'] !== null) {
                    $years[(int) $row['date']][$field] = $row['value'];
                }
            }
        }

        if (! $bulk && $successful === 0) {
            throw new RuntimeException('World Bank belum memberikan respons yang valid. Silakan coba kembali beberapa saat lagi.');
        }

        foreach ($years as $year => $values) EconomicIndicator::updateOrCreate(['country_id'=>$country->id,'year'=>$year], $values);
        if ($latest = EconomicIndicator::where('country_id',$country->id)->latest('year')->first()) $country->update(['gdp'=>$latest->gdp,'inflation_rate'=>$latest->inflation,'population'=>$latest->population]);
        return EconomicIndicator::where('country_id',$country->id)->orderBy('year')->get()->toArray();
    }

    public function weather(Country $country, bool $bulk = false): ?array
    {
        $port = $country->ports()->first();
        if (! $port) return null;
        try {
            $request = Http::withoutVerifying()->timeout($bulk ? 5 : 10);
            if (!$bulk) $request->retry(2, 200);
            $response = $request->get('https://api.open-meteo.com/v1/forecast', ['latitude'=>$port->latitude,'longitude'=>$port->longitude,'current'=>'temperature_2m,precipitation,weather_code,wind_speed_10m','timezone'=>'UTC']);
            $current = $response->json('current'); if (!is_array($current)) return null;
            $wind = (float)($current['wind_speed_10m'] ?? 0); $rain = (float)($current['precipitation'] ?? 0);
            $risk = min(100, round($wind * 1.5 + min(40, $rain * 4)));
            return WeatherSnapshot::create(['country_id'=>$country->id,'temperature'=>$current['temperature_2m'] ?? null,'precipitation'=>$rain,'wind_speed'=>$wind,'weather_code'=>$current['weather_code'] ?? null,'risk_score'=>$risk,'observed_at'=>$current['time'] ?? now()])->toArray();
        } catch (\Throwable) { return null; }
    }

    public function currency(Country $country): ?array
    {
        if (!$country->currency_code) return null;
        try {
            $rates = Cache::remember('sync-exchange-rates-usd', now()->addHour(), function () {
                $response = Http::withoutVerifying()->timeout(10)->retry(2, 200)->get('https://open.er-api.com/v6/latest/USD');
                return $response->successful() ? ($response->json('rates') ?? []) : [];
            });
            $rate = $rates[$country->currency_code] ?? null; if (!$rate) return null;
            $previous = CurrencySnapshot::where('country_id',$country->id)->latest('observed_at')->first();
            $change = $previous && (float)$previous->rate !== 0.0 ? (((float)$rate-(float)$previous->rate)/(float)$previous->rate)*100 : 0;
            return CurrencySnapshot::create(['country_id'=>$country->id,'quote_currency'=>$country->currency_code,'rate'=>$rate,'change_percent'=>$change,'observed_at'=>now()])->toArray();
        } catch (\Throwable) { return null; }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportCountries extends Command
{
    protected $signature = 'countries:import {--replace-generated}';
    protected $description = 'Import official country profiles from REST Countries';
    public function handle(): int
    {
        $key = config('services.restcountries.key');
        try {
            $count=0;
            if ($key) {
                $offset=0;
                do {
                    $response = Http::withoutVerifying()->withToken($key)->timeout(30)->retry(3,500)->get('https://api.restcountries.com/countries/v5', ['limit'=>100,'offset'=>$offset]); $response->throw();
                    $records=$response->json('data') ?? [];
                    foreach ($records as $record) {
                        $item=$record['attributes'] ?? $record; $code=strtoupper($item['codes']['alpha_2'] ?? $item['cca2'] ?? ''); if(strlen($code)!==2) continue;
                        Country::updateOrCreate(['code_iso2'=>$code], ['name'=>$item['names']['common'] ?? $item['name']['common'] ?? $code,'region'=>$item['region'] ?? null,'currency_code'=>array_key_first($item['currencies'] ?? []),'language'=>implode(', ',array_values($item['languages'] ?? [])),'population'=>$item['population'] ?? null]); $count++;
                    }
                    $offset+=count($records);
                } while(count($records)===100);
            } else {
                $response = Http::withoutVerifying()->timeout(45)->retry(3,700)->get('https://raw.githubusercontent.com/mledoze/countries/master/countries.json');
                $response->throw();
                foreach ($response->json() as $item) {
                    $code=strtoupper($item['cca2'] ?? ''); if(strlen($code)!==2) continue;
                    Country::updateOrCreate(['code_iso2'=>$code], ['name'=>$item['name']['common'] ?? $code,'region'=>$item['region'] ?? null,'currency_code'=>array_key_first($item['currencies'] ?? []),'language'=>implode(', ',array_values($item['languages'] ?? [])),'population'=>$item['population'] ?? null]); $count++;
                }
            }
            Country::updateOrCreate(['code_iso2'=>'XK'], ['name'=>'Kosovo','region'=>'Europe','currency_code'=>'EUR','language'=>'Albanian, Serbian','population'=>null]);
            if($this->option('replace-generated')) Country::where('name','like','Territory Hub Zone%')->orWhere('name','like','Jurisdiction %')->delete();
            $this->info("Imported {$count} official country and territory profiles. Total: ".Country::count()); return self::SUCCESS;
        } catch(\Throwable $e) { $this->error('REST Countries unavailable: '.$e->getMessage()); return self::FAILURE; }
    }
}

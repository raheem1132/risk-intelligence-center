<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportUnLocodePorts extends Command
{
    protected $signature = 'ports:import-unlocode {--target=12000 : Stop after the database reaches this total}';
    protected $description = 'Add real port-function locations from the UNECE UN/LOCODE dataset';

    public function handle(): int
    {
        $url = 'https://raw.githubusercontent.com/datasets/un-locode/main/data/code-list.csv';
        try {
            $csv = Http::withoutVerifying()->timeout(90)->retry(3, 700)->get($url)->throw()->body();
            $stream = fopen('php://temp', 'r+'); fwrite($stream, $csv); rewind($stream);
            $headers = array_map(fn($h) => strtolower(trim($h)), fgetcsv($stream));
            $countries = Country::all()->keyBy('code_iso2'); $target=(int)$this->option('target'); $total=Port::count(); $added=0; $skipped=0;
            while (($values=fgetcsv($stream)) !== false && $total < $target) {
                if(count($values)!==count($headers)) continue; $row=array_combine($headers,$values);
                $functions=$this->value($row,['function']); $coords=$this->value($row,['coordinates']);
                if(!str_contains($functions,'1') || !$coords) continue;
                [$lat,$lng]=$this->coordinates($coords); if($lat===null || $lng===null) continue;
                $countryCode=strtoupper($this->value($row,['country'])); $location=strtoupper($this->value($row,['location']));
                if(strlen($countryCode)!==2 || !$location) continue; $unlocode=$countryCode.$location;
                if(Port::where('wpi_number','UNLOCODE:'.$unlocode)->exists() || Port::where('details','like','%'.$unlocode.'%')->exists()){ $skipped++; continue; }
                $country=$countries->get($countryCode); $name=$this->value($row,['name','namewodiacritics']); if(!$name) continue;
                Port::create(['country_id'=>$country?->id,'port_name'=>$name,'country_code'=>$countryCode,'country_name'=>$country?->name ?: $countryCode,'latitude'=>$lat,'longitude'=>$lng,'risk_status'=>'Low','risk_score'=>0,'details'=>'UN/LOCODE: '.$unlocode.' · Function: '.$functions,'wpi_number'=>'UNLOCODE:'.$unlocode,'harbor_size'=>null,'harbor_type'=>'UN/LOCODE port function','source'=>'UNECE UN/LOCODE 2025-1']); $added++; $total++;
                if($added%1000===0)$this->info("Added {$added}; total {$total}");
            }
            fclose($stream); $this->info('UN/LOCODE import complete. Added '.$added.', skipped duplicates '.$skipped.', total '.$total.'.'); return self::SUCCESS;
        } catch(\Throwable $e){$this->error('UN/LOCODE import failed: '.$e->getMessage());return self::FAILURE;}
    }

    private function value(array $row,array $keys): string { foreach($keys as $key)if(isset($row[$key]))return trim((string)$row[$key]);return ''; }
    private function coordinates(string $value): array
    {
        if(!preg_match('/^(\d{2})(\d{2})([NS])\s+(\d{3})(\d{2})([EW])$/',trim($value),$m))return [null,null];
        $lat=(int)$m[1]+(int)$m[2]/60; $lng=(int)$m[4]+(int)$m[5]/60;
        if($m[3]==='S')$lat*=-1;if($m[6]==='W')$lng*=-1;return [round($lat,6),round($lng,6)];
    }
}

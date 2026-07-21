<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportWorldPorts extends Command
{
    protected $signature = 'ports:import-wpi {--limit=0 : Maximum records, zero imports all} {--replace : Remove legacy generated ports first}';
    protected $description = 'Import official NGA World Port Index records from its public ArcGIS service';

    public function handle(): int
    {
        // NGA data mirrored by the U.S. Department of Energy ArcGIS service.
        $layerUrl = 'https://arcgis.netl.doe.gov/server/rest/services/Hosted/CCS_EJ_SJ_Data/FeatureServer/17';
        try {
            if ($this->option('replace')) Port::whereNull('wpi_number')->delete();
            $offset = 0; $pageSize = 1000; $imported = 0; $limit = (int)$this->option('limit');
            do {
                $take = $limit ? min($pageSize, $limit-$imported) : $pageSize; if ($take <= 0) break;
                $json = Http::withoutVerifying()->timeout(60)->retry(3, 700)->get("{$layerUrl}/query", ['where'=>'1=1','outFields'=>'*','returnGeometry'=>'true','outSR'=>4326,'resultOffset'=>$offset,'resultRecordCount'=>$take,'f'=>'json'])->throw()->json();
                $features = $json['features'] ?? [];
                foreach ($features as $feature) {
                    $a = array_change_key_case($feature['attributes'] ?? [], CASE_LOWER); $g = $feature['geometry'] ?? [];
                    $value = fn (array $keys) => collect($keys)->map(fn($key)=>$a[strtolower($key)] ?? null)->first(fn($v)=>$v!==null && $v!=='');
                    $name = $value(['PORT_NAME','PORTNAME','MAIN_PORT_NAME','NAME']); if (!$name) continue;
                    $unlocode = strtoupper((string)$value(['UNLOCODE']));
                    $code = strlen($unlocode) >= 2 ? substr($unlocode,0,2) : strtoupper((string)$value(['COUNTRY_CODE','ISO2','ISO_2']));
                    $country = strlen($code)===2 ? Country::where('code_iso2',$code)->first() : Country::where('name','like','%'.$code.'%')->first();
                    Port::updateOrCreate(['wpi_number'=>(string)($value(['INDEX_NO','WPI_NUMBER','WPI_NO','WPINUMBER','OBJECTID']) ?: md5($name.($g['x'] ?? '').($g['y'] ?? '')))], ['country_id'=>$country?->id,'port_name'=>$name,'country_code'=>$country?->code_iso2 ?: substr($code,0,5),'country_name'=>$country?->name ?: $value(['WPI_CC','COUNTRY','COUNTRY_NAME']),'latitude'=>$g['y'] ?? $value(['LATITUDE','LAT_DEC']),'longitude'=>$g['x'] ?? $value(['LONGITUDE','LON_DEC']),'harbor_size'=>$value(['HARBOR_SIZE_CODE','HARBOR_SIZE','HARBORSIZE']),'harbor_type'=>$value(['HARBOR_TYPE_CODE','HARBOR_TYPE','HARBORTYPE']),'details'=>'UN/LOCODE: '.$unlocode,'source'=>'NGA World Port Index (U.S. DOE mirror)']);
                    $imported++;
                }
                $offset += count($features); $this->info("Imported {$imported} official ports...");
            } while (count($features)===$take && (!$limit || $imported<$limit));
            $this->info("World Port Index import complete: {$imported} records."); return self::SUCCESS;
        } catch (\Throwable $e) { $this->error('NGA service unavailable: '.$e->getMessage()); return self::FAILURE; }
    }
}

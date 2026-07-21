<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\GlobalDataService;
use App\Services\RiskScoringService;
use Illuminate\Console\Command;

class SyncGlobalData extends Command
{
    protected $signature = 'data:sync {country? : ISO2 code} {--all} {--missing : Only countries without a risk snapshot}';
    protected $description = 'Synchronize REST Countries, World Bank, Open-Meteo, exchange rate, news and risk snapshots';
    public function handle(GlobalDataService $data, RiskScoringService $risk): int
    {
        $query = Country::query();
        if (!$this->option('all')) $query->where('code_iso2', strtoupper($this->argument('country') ?: 'ID'));
        if ($this->option('missing')) $query->whereDoesntHave('riskScores');
        foreach ($query->get() as $country) { $this->info('Syncing '.$country->name); $data->syncCountry($country,$this->option('all')); $risk->calculateRisk($country); }
        return self::SUCCESS;
    }
}

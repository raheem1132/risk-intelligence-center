<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TransactionalMailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class DispatchRiskAlerts extends Command
{
    protected $signature = 'alerts:dispatch {--weekly : Send weekly watchlist digests}';
    protected $description = 'Dispatch threshold alerts or weekly digests for persisted user preferences';

    public function handle(TransactionalMailService $mailer): int
    {
        User::with(['preference', 'watchlists.country.riskScores' => fn ($query) => $query->latest()->limit(1)])
            ->chunkById(100, function ($users) use ($mailer): void {
                foreach ($users as $user) {
                    $preference = $user->preference;
                    if (! $preference) continue;

                    if ($this->option('weekly')) {
                        if (! $preference->weekly_digest) continue;
                        $items = $user->watchlists->map(function ($watchlist) {
                            $risk = $watchlist->country->riskScores->first();
                            return $risk ? ['country'=>$watchlist->country->name, 'score'=>(float)$risk->total_score, 'status'=>$risk->status] : null;
                        })->filter()->values()->all();
                        if ($items !== []) $mailer->sendWeeklyDigest($user, $items);
                        continue;
                    }

                    if (! $preference->email_alerts) continue;
                    foreach ($user->watchlists as $watchlist) {
                        $risk = $watchlist->country->riskScores->first();
                        if (! $risk || (float)$risk->total_score < $preference->risk_threshold) continue;
                        $key = "risk-alert:{$user->id}:{$watchlist->country_id}:{$risk->id}";
                        if (Cache::add($key, true, now()->addDay())) {
                            $mailer->sendRiskAlert($user, $watchlist->country, $risk);
                        }
                    }
                }
            });

        $this->info('Risk notifications dispatched.');
        return self::SUCCESS;
    }
}

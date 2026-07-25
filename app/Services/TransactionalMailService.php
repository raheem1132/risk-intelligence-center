<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TransactionalMailService
{
    public function send(string $to, string $subject, string $html): void
    {
        $key = config('services.resend.key');
        if (blank($key)) throw new RuntimeException('RESEND_API_KEY belum dikonfigurasi.');

        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name', 'SupplyGuard Intelligence');
        $response = Http::withToken($key)->timeout(15)->retry(2, 300, throw: false)
            ->post('https://api.resend.com/emails', [
                'from' => "{$fromName} <{$fromAddress}>",
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Email provider menolak pengiriman: '.($response->json('message') ?: $response->status()));
        }
    }

    public function sendRiskAlert(User $user, Country $country, RiskScore $risk): void
    {
        $url = route('reports.country', $country->code_iso2);
        $this->send($user->email, "SupplyGuard risk alert: {$country->name}", "<h2>Risk threshold terlampaui</h2><p>Skor risiko <strong>{$country->name}</strong> mencapai <strong>{$risk->total_score}/100</strong> ({$risk->status}).</p><p>Cuaca {$risk->weather_risk} · Inflasi {$risk->inflation_risk} · Kurs {$risk->currency_risk} · Berita {$risk->news_risk}</p><p><a href=\"{$url}\">Buka country report</a></p>");
    }

    public function sendWeeklyDigest(User $user, array $items): void
    {
        $rows = collect($items)->map(fn ($item) => "<li>{$item['country']}: {$item['score']}/100 · {$item['status']}</li>")->implode('');
        $url = route('watchlist');
        $this->send($user->email, 'SupplyGuard weekly risk digest', "<h2>Weekly risk digest</h2><ul>{$rows}</ul><p><a href=\"{$url}\">Buka Watchlist</a></p>");
    }
}

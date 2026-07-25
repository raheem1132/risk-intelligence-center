<?php

namespace App\Notifications;

use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RiskThresholdAlert extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Country $country,
        private readonly RiskScore $risk,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("SupplyGuard risk alert: {$this->country->name}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Skor risiko {$this->country->name} mencapai {$this->risk->total_score}/100 ({$this->risk->status}).")
            ->line("Cuaca {$this->risk->weather_risk} · Inflasi {$this->risk->inflation_risk} · Kurs {$this->risk->currency_risk} · Berita {$this->risk->news_risk}")
            ->action('Buka country report', route('reports.country', $this->country->code_iso2))
            ->line('Peringatan ini dikirim berdasarkan watchlist dan threshold akun Anda.');
    }
}

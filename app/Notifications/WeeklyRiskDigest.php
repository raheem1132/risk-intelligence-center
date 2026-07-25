<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyRiskDigest extends Notification
{
    use Queueable;

    public function __construct(private readonly array $items) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('SupplyGuard weekly risk digest')
            ->greeting("Halo {$notifiable->name},")
            ->line('Ringkasan terbaru negara dalam watchlist Anda:');

        foreach ($this->items as $item) {
            $mail->line("{$item['country']}: {$item['score']}/100 · {$item['status']}");
        }

        return $mail->action('Buka Watchlist', route('watchlist'))->line('Data mengikuti snapshot terbaru yang tersedia.');
    }
}

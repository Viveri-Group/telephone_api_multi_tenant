<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TeamsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $card)
    {
    }

    public function via($notifiable): array
    {
        return ['teams'];
    }

    public function toTeams($notifiable): void
    {
        $webhookUrl = config('system.NOTIFICATIONS.teams.webhook_url');

        Http::post($webhookUrl, $this->card);
    }
}

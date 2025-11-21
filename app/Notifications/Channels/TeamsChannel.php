<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class TeamsChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toTeams')) {
            return;
        }

        $notification->toTeams($notifiable);
    }
}

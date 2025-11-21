<?php

namespace App\Http\Controllers;

use App\Helpers\TeamsNotificationCardBuilder;
use App\Models\Participant;
use App\Notifications\TeamsNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class EntrantAudioDownloadController extends Controller
{
    public function __invoke(Request $request, Participant $participant)
    {
        $userId = Config::get('system.ITV_S3_USER_ID');

        $path = "{$userId}/{$participant->competition->id}/{$participant->telephone}_{$participant->call_id}.wav";

        if (!Storage::disk('s3')->exists($path)) {
            if (!app()->runningUnitTests()) {
                $this->notifyNotFound($participant, $path);
            }

            return response()->json([
                'error' => 'Audio file not found.',
                'message' => 'Please note: audio files are normally available within 30 minutes, though in busy periods it may take longer.'
            ], 404);
        }

        return response()->json([
            'url' => Storage::disk('s3')->temporaryUrl( $path, now()->addMinutes(20))
        ]);
    }

    protected function notifyNotFound(Participant $participant, string $path)
    {
        $card = (new TeamsNotificationCardBuilder())
            ->status(
                '🎧 VOICE RECORDING NOT PRESENT',
                'medium',
                "A voice recording was requested but it was found to be not present. \n\nParticipant UUID: {$participant->uuid}\n\nPath: {$path}"
            )
            ->build();

        Notification::route('teams', 'default')->notify(new TeamsNotification($card));
    }
}

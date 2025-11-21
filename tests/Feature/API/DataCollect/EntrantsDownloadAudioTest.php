<?php

namespace Tests\Feature\API\DataCollect;

use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EntrantsDownloadAudioTest extends TestCase
{
    public function test_i_can_get_audio_from_a_specific_participant()
    {
        $this->login();

        Storage::fake('s3');

        $competition = Competition::factory()->create(['id' => 8]);

        $participant = Participant::factory()->create([
            'call_id' => '17903710',
            'competition_id' => $competition->id,
            'telephone' => '447505444557',
        ]);

        // Create a dummy wav file (in memory)
        $wav = UploadedFile::fake()->create('test.wav', 10, 'audio/wav');

        // Put it into the faked S3
        Storage::disk('s3')->putFileAs('199999/8', $wav, '447505444557_17903710.wav');

        $this->getJson(route('entrant.audio', $participant->uuid))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('url');
            });
    }

    public function test_i_get_errors_if_participant_id_does_not_exist()
    {
        $this->login();

        $this->getJson(route('entrant.audio', 2))
            ->assertNotFound();
    }

    public function test_audio_file_not_present()
    {
        Notification::fake();

        $this->login();

        Storage::fake('s3');

        $competition = Competition::factory()->create(['id' => 8]);

        $participant = Participant::factory()->create([
            'call_id' => '17903710',
            'competition_id' => $competition->id,
            'telephone' => '447505444557',
        ]);

        $this->getJson(route('entrant.audio', $participant->uuid))
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('error', 'Audio file not found.')
                    ->where('message', 'Please note: audio files are normally available within 30 minutes, though in busy periods it may take longer.');
            });
    }
}

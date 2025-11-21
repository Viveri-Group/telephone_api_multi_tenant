<?php

namespace Tests\Feature\API\DataCollect;

use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EntrantsDownloadTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2025-08-07T08:20:00Z');

        Config::set('system.DOWNLOAD.ENTRIES_DOWNLOAD_RESULTS_PER_PAGE', 1);

        $this->login();

        $this->competition = Competition::factory()->create();

        Participant::factory()->count(3)->create([
            'competition_id' => $this->competition->id,
            'call_start' => now(),
            'cli_presentation' => 2,
        ]);
    }

    public function test_validation()
    {
        $this->postJson(route('download.entrants', $this->competition))
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'date_from')
                    ->where('data.1.source', 'date_to');
            });
    }

    public function test_response_is_as_expected()
    {
        $participants = Participant::all();

        $this->postJson(route('download.entrants', $this->competition),
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use($participants) {
                $participant = $participants->get(2);

                return $json
                    ->where('participants.data.0.reference', $participant->uuid)
                    ->has('participants.data.0.telephone')
                    ->has('participants.data.0.call_start')
                    ->where('participants.current_page', 1)
                    ->count('participants.data', 1)
                    ->where('participants.to', 1)
                    ->where('participants.total', 3)
                    ->etc();
            });
    }
    public function test_i_can_get_participants_from_page_1()

    {
        $this->postJson(route('download.entrants', $this->competition),
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('participants.data.0.reference')
                    ->has('participants.data.0.telephone')
                    ->has('participants.data.0.call_start')
                    ->where('participants.current_page', 1)
                    ->count('participants.data', 1)
                    ->where('participants.to', 1)
                    ->where('participants.total', 3)
                    ->etc();
            });
    }

    public function test_phone_number_is_shown()
    {
        Participant::query()->update(['cli_presentation' => 0]);

        $this->postJson(route('download.entrants', $this->competition),
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('participants.data.0.reference')
                    ->where('participants.data.0.telephone', function ($value) {
                        return $value !== config('system.DOWNLOAD.DEFAULT_HIDDEN_CLI_PRESENTATION');
                    })
                    ->has('participants.data.0.call_start')
                    ->where('participants.current_page', 1)
                    ->count('participants.data', 1)
                    ->where('participants.to', 1)
                    ->where('participants.total', 3)
                    ->etc();
            });
    }

    public function test_phone_number_is_not_shown_when_cli_presentation_is_set_to_4()
    {
        Participant::query()->update(['cli_presentation' => 4]);

        $this->postJson(route('download.entrants', $this->competition),
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('participants.data.0.reference')
                    ->where('participants.data.0.telephone', config('system.DOWNLOAD.DEFAULT_HIDDEN_CLI_PRESENTATION'))
                    ->has('participants.data.0.call_start')
                    ->where('participants.current_page', 1)
                    ->count('participants.data', 1)
                    ->where('participants.to', 1)
                    ->where('participants.total', 3)
                    ->etc();
            });
    }

    public function test_i_can_get_participants_from_subsequent_pages()
    {
        $route = route('download.entrants', $this->competition) . '?page=2';

        $this->postJson($route,
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('participants.data.0.reference')
                    ->has('participants.data.0.telephone')
                    ->has('participants.data.0.call_start')
                    ->where('participants.current_page', 2)
                    ->count('participants.data', 1)
                    ->where('participants.to', 2)
                    ->where('participants.total', 3)
                    ->etc();
            });
    }

    public function test_if_i_request_pages_that_dont_exist_i_see_errors()
    {
        $route = route('download.entrants', $this->competition) . '?page=10';

        $this->postJson($route,
            [
                'date_from' => '2025-08-07T08:19:00Z',
                'date_to' => '2025-08-07T08:21:00Z',
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('participants.current_page', 10)
                    ->count('participants.data', 0)
                    ->whereNull('participants.to')
                    ->where('participants.total', 3)
                    ->etc();
            });
    }
}

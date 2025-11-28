<?php

namespace Tests\Feature\API\Caller;

use App\Models\Competition;
use App\Models\FailedEntry;
use App\Models\Participant;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CallerHistoryTest extends TestCase
{
    public function test_validation()
    {
        $this->login();

        $this->post(route('caller.history'))
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'caller_phone_number')
                    ->etc();
            });
    }

    public function test_works_as_expected()
    {
        $this->login();

        $participant = Participant::factory(['telephone' => '441604123123', 'call_start' => now(), 'call_end' => now()->addMinute()])->create();

        $failedEntry = FailedEntry::factory(['caller_phone_number' => '441604123123', 'call_start' => now(), 'call_end' => now()->addMinute()])->create();

        $this->post(route('caller.history'), ['caller_phone_number' => '441604123123'])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($participant, $failedEntry) {
                return $json
                    ->has('participants', 1)
                    ->where('participants.0.id', $participant->id)
                    ->where('participants.0.type', 'participant')
                    ->where('participants.0.attributes', [
                        'uuid' => $participant->uuid,
                        'call_end' => $participant->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participant->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participant->competition_id,
                        'call_id' => (int)$participant->call_id,
                        'created' => $participant->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 1 of 10',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participant->competition_phone_line_id,
                        'competition_phone_number' => $participant->competition_phone_number,
                        'caller_phone_number' => $participant->telephone,
                    ])
                    ->has('failed_entries', 1)
                    ->where('failed_entries.0.id', $failedEntry->id)
                    ->where('failed_entries.0.type', 'failed-entry')
                    ->where('failed_entries.0.attributes', [
                        'competition_id' => $failedEntry->competition_id,
                        'call_id' => (int)$failedEntry->call_id,
                        'competition_phone_number' => $failedEntry->phone_number,
                        'caller_phone_number' => $failedEntry->caller_phone_number,
                        'reason' => $failedEntry->reason,
                        'call_start' => $failedEntry->call_start->format('Y-m-d H:i:s'),
                        'call_end' => $failedEntry->call_end->format('Y-m-d H:i:s'),
                    ]);
            });
    }

    public function test_out_of_total_counter_works_as_expected_on_participants()
    {
        $this->login();

        $compA = Competition::factory(['max_entries' => 10])->create();
        $compB = Competition::factory(['max_entries' => 20])->create();
        $compC = Competition::factory(['max_entries' => 30])->create();

        $participants = [
            'B1' => Participant::factory(['call_id'=>1001,'competition_id' => $compB->id, 'telephone' => '441604123123', 'call_start' => now(), 'call_end' => now()->addMinute(), 'audio_file_number' => 123,])->create(),
            'C1' => Participant::factory(['call_id'=>1002,'competition_id' => $compC->id, 'telephone' => '441604123123', 'call_start' => now()->subMinute(), 'call_end' => now()])->create(),
            'A1' => Participant::factory(['call_id'=>1003,'competition_id' => $compA->id, 'telephone' => '441604123123', 'call_start' => now()->subMinutes(2), 'call_end' => now()->subMinute()])->create(),
            'B2' => Participant::factory(['call_id'=>1004,'competition_id' => $compB->id, 'telephone' => '441604123123', 'call_start' => now()->subMinutes(3), 'call_end' => now()->subMinutes(2)])->create(),
            'A2' => Participant::factory(['call_id'=>1005,'competition_id' => $compA->id, 'telephone' => '441604123123', 'call_start' => now()->subMinutes(4), 'call_end' => now()->subMinutes(3)])->create(),
            'A3' => Participant::factory(['call_id'=>1006,'competition_id' => $compA->id, 'telephone' => '441604123123', 'call_start' => now()->subMinutes(5), 'call_end' => now()->subMinutes(4)])->create(),
        ];

        $this->post(route('caller.history'), ['caller_phone_number' => '441604123123'])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($participants) {

                return $json
                    ->has('participants', 6)
                    ->where('participants.0.id', $participants['A1']->id)
                    ->where('participants.0.type', 'participant')
                    ->where('participants.0.attributes', [
                        'uuid' => $participants['A1']->uuid,
                        'call_end' => $participants['A1']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['A1']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['A1']->competition_id,
                        'call_id' => $participants['A1']->call_id,
                        'created' => $participants['A1']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 1 of 10',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participants['A1']->competition_phone_line_id,
                        'competition_phone_number' => $participants['A1']->competition_phone_number,
                        'caller_phone_number' => $participants['A1']->telephone,
                    ])

                    ->where('participants.1.id', $participants['A2']->id)
                    ->where('participants.1.type', 'participant')
                    ->where('participants.1.attributes', [
                        'uuid' => $participants['A2']->uuid,
                        'call_end' => $participants['A2']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['A2']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['A2']->competition_id,
                        'call_id' => $participants['A2']->call_id,
                        'created' => $participants['A2']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 2 of 10',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participants['A2']->competition_phone_line_id,
                        'competition_phone_number' => $participants['A2']->competition_phone_number,
                        'caller_phone_number' => $participants['A2']->telephone,
                    ])

                    ->where('participants.2.id', $participants['A3']->id)
                    ->where('participants.2.type', 'participant')
                    ->where('participants.2.attributes', [
                        'uuid' => $participants['A3']->uuid,
                        'call_end' => $participants['A3']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['A3']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['A3']->competition_id,
                        'call_id' => $participants['A3']->call_id,
                        'created' => $participants['A3']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 3 of 10',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participants['A3']->competition_phone_line_id,
                        'competition_phone_number' => $participants['A3']->competition_phone_number,
                        'caller_phone_number' => $participants['A3']->telephone,
                    ])

                    ->where('participants.3.id', $participants['B1']->id)
                    ->where('participants.3.type', 'participant')
                    ->where('participants.3.attributes', [
                        'uuid' => $participants['B1']->uuid,
                        'call_end' => $participants['B1']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['B1']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['B1']->competition_id,
                        'call_id' => $participants['B1']->call_id,
                        'created' => $participants['B1']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 1 of 20',
                        'is_free_entry' => false,
                        'audio_file_number' => 123,
                        'phone_line_id' => $participants['B1']->competition_phone_line_id,
                        'competition_phone_number' => $participants['B1']->competition_phone_number,
                        'caller_phone_number' => $participants['B1']->telephone,
                    ])

                    ->where('participants.4.id', $participants['B2']->id)
                    ->where('participants.4.type', 'participant')
                    ->where('participants.4.attributes', [
                        'uuid' => $participants['B2']->uuid,
                        'call_end' => $participants['B2']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['B2']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['B2']->competition_id,
                        'call_id' => $participants['B2']->call_id,
                        'created' => $participants['B2']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 2 of 20',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participants['B2']->competition_phone_line_id,
                        'competition_phone_number' => $participants['B2']->competition_phone_number,
                        'caller_phone_number' => $participants['B2']->telephone,
                    ])

                    ->where('participants.5.id', $participants['C1']->id)
                    ->where('participants.5.type', 'participant')
                    ->where('participants.5.attributes', [
                        'uuid' => $participants['C1']->uuid,
                        'call_end' => $participants['C1']->call_end->format('Y-m-d H:i:s'),
                        'call_start' => $participants['C1']->call_start->format('Y-m-d H:i:s'),
                        'competition_id' => $participants['C1']->competition_id,
                        'call_id' => $participants['C1']->call_id,
                        'created' => $participants['C1']->created_at->format('Y-m-d H:i:s'),
                        'entry_status' => 'Entry 1 of 30',
                        'is_free_entry' => false,
                        'audio_file_number' => null,
                        'phone_line_id' => $participants['C1']->competition_phone_line_id,
                        'competition_phone_number' => $participants['C1']->competition_phone_number,
                        'caller_phone_number' => $participants['C1']->telephone,
                    ])

                    ->has('failed_entries', 0);
            });
    }

    public function test_no_data_response()
    {
        $this->login();

        $this->post(route('caller.history'), ['caller_phone_number' => '441604123123'])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('participants', [])
                    ->where('failed_entries', []);
            });
    }

    public function test_fails_with_invalid_number_type()
    {
        $this->login();

        $this->post(route('caller.history'), ['caller_phone_number' => '01604123123'])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('message', 'The caller phone number field format is invalid.')
                    ->where('data.0.source', 'caller_phone_number')
                    ->where('data.0.message', 'The caller phone number field format is invalid.');
            });
    }
}

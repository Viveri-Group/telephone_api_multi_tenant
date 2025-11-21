<?php

namespace Tests\Feature\Web\Participants;

use App\Models\Competition;
use App\Models\Participant;
use Tests\TestCase;

class ParticipantsTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.participants.index'))
            ->assertStatus(302);
    }

    public function test_page_shows_as_expected()
    {
        $this->login();

        $participant = Participant::factory()->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($participant->caller_phone_number);
    }

    public function test_competition_search_works_as_expected()
    {
        $this->login();

        $competition = Competition::factory()->create();

        $participantA = Participant::factory(['competition_id' => $competition->id])->create();
        $participantB = Participant::factory()->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'competition_id' => $competition->id
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }

    public function test_call_id_search_works_as_expected()
    {
        $this->login();

        $participantA = Participant::factory(['call_id' => '123456'])->create();
        $participantB = Participant::factory()->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'call_id' => '123456'
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }

    public function test_telephone_search_works_as_expected()
    {
        $this->login();

        $participantA = Participant::factory(['telephone' => '23456'])->create();
        $participantB = Participant::factory()->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'telephone' => '234'
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }

    public function test_call_start_search_works_as_expected()
    {
        $this->login();

        $participantA = Participant::factory(['call_start' => now()->subDays(2)])->create();
        $participantB = Participant::factory(['call_start' => now()->subDays(3)])->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->subDays(3)->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }

    public function test_drawn_with_false_search_works_as_expected()
    {
        $this->login();

        $participantA = Participant::factory()->create();
        $participantB = Participant::factory(['drawn_at' => now()->subDays(2)])->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'drawn' => false
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }

    public function test_drawn_with_date_search_works_as_expected()
    {
        $this->login();

        $participantA = Participant::factory(['drawn_at' => now()->subDays(2)])->create();
        $participantB = Participant::factory()->create();

        $this->get(route('web.participants.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'drawn' => true
            ]))
            ->assertOk()
            ->assertSee($participantA->caller_phone_number)
            ->assertDontSee($participantB->caller_phone_number);
    }
}

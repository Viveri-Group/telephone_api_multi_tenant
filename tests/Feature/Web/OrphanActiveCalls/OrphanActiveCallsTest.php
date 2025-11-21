<?php

namespace Tests\Feature\Web\OrphanActiveCalls;

use App\Models\ActiveCallOrphan;
use App\Models\Competition;
use Tests\TestCase;

class OrphanActiveCallsTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.orphan-active-calls.index'))
            ->assertStatus(302);
    }

    public function test_page_shows_as_expected()
    {
        $this->login();

        $orphan = ActiveCallOrphan::factory()->create();

        $this->get(route('web.orphan-active-calls.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($orphan->caller_phone_number);
    }

    public function test_competition_search_works_as_expected()
    {
        $this->login();

        $competition = Competition::factory()->create();

        $orphanA = ActiveCallOrphan::factory(['competition_id' => $competition->id])->create();
        $orphanB = ActiveCallOrphan::factory()->create();

        $this->get(route('web.orphan-active-calls.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'competition_id' => $competition->id
            ]))
            ->assertOk()
            ->assertSee($orphanA->caller_phone_number)
            ->assertDontSee($orphanB->caller_phone_number);
    }

    public function test_call_id_search_works_as_expected()
    {
        $this->login();

        $orphanA = ActiveCallOrphan::factory(['call_id' => '123456'])->create();
        $orphanB = ActiveCallOrphan::factory()->create();

        $this->get(route('web.orphan-active-calls.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'call_id' => '123456'
            ]))
            ->assertOk()
            ->assertSee($orphanA->caller_phone_number)
            ->assertDontSee($orphanB->caller_phone_number);
    }

    public function test_phone_number_search_works_as_expected()
    {
        $this->login();

        $orphanA = ActiveCallOrphan::factory(['phone_number' => '123456'])->create();
        $orphanB = ActiveCallOrphan::factory()->create();

        $this->get(route('web.orphan-active-calls.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'phone_number' => '123456'
            ]))
            ->assertOk()
            ->assertSee($orphanA->caller_phone_number)
            ->assertDontSee($orphanB->caller_phone_number);
    }

    public function test_caller_phone_number_search_works_as_expected()
    {
        $this->login();

        $orphanA = ActiveCallOrphan::factory(['caller_phone_number' => '123456'])->create();
        $orphanB = ActiveCallOrphan::factory()->create();

        $this->get(route('web.orphan-active-calls.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'caller_phone_number' => '123456'
            ]))
            ->assertOk()
            ->assertSee($orphanA->caller_phone_number)
            ->assertDontSee($orphanB->caller_phone_number);
    }
}

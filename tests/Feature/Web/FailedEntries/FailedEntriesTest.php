<?php

namespace Tests\Feature\Web\FailedEntries;

use App\Models\Competition;
use App\Models\FailedEntry;
use Tests\TestCase;

class FailedEntriesTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.entries.failed.index'))
            ->assertStatus(302);
    }

    public function test_failed_entries_page_displays_result()
    {
        $this->login();

        $failedEntry = FailedEntry::factory()->create();

        $this->get(route('web.entries.failed.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($failedEntry->caller_phone_number);
    }

    public function test_competition_search_displays_result_as_expected()
    {
        $this->login();

        $competition = Competition::factory()->create();

        $failedEntryA = FailedEntry::factory(['competition_id' => $competition->id])->create();
        $failedEntryB = FailedEntry::factory()->create();

        $this->get(route('web.entries.failed.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'competition_id' => $competition->id
            ]))
            ->assertOk()
            ->assertSee($failedEntryA->caller_phone_number)
            ->assertDontSee($failedEntryB->caller_phone_number);
    }

    public function test_call_id_search_displays_result_as_expected()
    {
        $this->login();

        $failedEntryA = FailedEntry::factory(['call_id' => '123456'])->create();
        $failedEntryB = FailedEntry::factory()->create();

        $this->get(route('web.entries.failed.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'call_id' => '123456'
            ]))
            ->assertOk()
            ->assertSee($failedEntryA->caller_phone_number)
            ->assertDontSee($failedEntryB->caller_phone_number);
    }

    public function test_phone_number_search_displays_result_as_expected()
    {
        $this->login();

        $failedEntryA = FailedEntry::factory(['phone_number' => '123456'])->create();
        $failedEntryB = FailedEntry::factory()->create();

        $this->get(route('web.entries.failed.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'phone_number' => '123456'
            ]))
            ->assertOk()
            ->assertSee($failedEntryA->caller_phone_number)
            ->assertDontSee($failedEntryB->caller_phone_number);
    }

    public function test_caller_phone_number_search_displays_result_as_expected()
    {
        $this->login();

        $failedEntryA = FailedEntry::factory(['caller_phone_number' => '123456'])->create();
        $failedEntryB = FailedEntry::factory()->create();

        $this->get(route('web.entries.failed.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'caller_phone_number' => '123456'
            ]))
            ->assertOk()
            ->assertSee($failedEntryA->caller_phone_number)
            ->assertDontSee($failedEntryB->caller_phone_number);
    }

//    public function test_reason_search_displays_result_as_expected()
//    {
//        $this->login();
//
//        $failedEntryA = FailedEntry::factory(['reason' => 'foo_reason'])->create();
//        $failedEntryB = FailedEntry::factory()->create();
//
//        $this->get(route('web.entries.failed.index',
//            [
//                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
//                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
//                'reason' => 'foo_reason'
//            ]))
//            ->assertOk()
//            ->assertSee($failedEntryA->caller_phone_number)
//            ->assertDontSee($failedEntryB->caller_phone_number);
//    }
}

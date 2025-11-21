<?php

namespace Tests\Feature\Web\MaxCapacityCallLogs;

use App\Models\ActiveCallOrphan;
use App\Models\MaxCapacityCallLog;
use Tests\TestCase;

class MaxCapacityCallLogsTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.max-capacity-call-logs.index'))
            ->assertStatus(302);
    }

    public function test_page_shows_as_expected()
    {
        $this->login();

        $maxCapacityLog = MaxCapacityCallLog::factory()->create();

        $this->get(route('web.max-capacity-call-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($maxCapacityLog->caller_phone_number);
    }

    public function test_search_works_as_expected()
    {
        $this->login();

        $maxCapacityLogA = MaxCapacityCallLog::factory()->create();
        $maxCapacityLogB = MaxCapacityCallLog::factory()->create();

        $this->get(route('web.max-capacity-call-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'call_id' => $maxCapacityLogB->call_id
            ]))
            ->assertOk()
            ->assertSee($maxCapacityLogB->caller_phone_number)
            ->assertDontSee($maxCapacityLogA->caller_phone_number);
    }
}

<?php

namespace Tests\Feature\Web\LogClearDown;

use App\Models\ActiveCall;
use App\Models\ActiveCallOrphan;
use App\Models\APIRequestLog;
use App\Models\EntrantRoundCount;
use App\Models\FailedEntry;
use App\Models\MaxCapacityCallLog;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class LogClearDownTest extends TestCase
{
    public function test_log_clear_down()
    {
        $this->login();

        ActiveCall::factory()->create();
        ActiveCallOrphan::factory()->create();
        APIRequestLog::factory()->create();
        EntrantRoundCount::factory()->create();
        FailedEntry::factory()->create();
        MaxCapacityCallLog::factory()->create();

        $this->get(route('web.log-clear-down'))
            ->assertRedirect()
            ->assertSessionHas('flash-data');

        $this->assertCount(0, ActiveCall::all());
        $this->assertCount(0, ActiveCallOrphan::all());
        $this->assertCount(0, APIRequestLog::all());
        $this->assertCount(0, EntrantRoundCount::all());
        $this->assertCount(0, FailedEntry::all());
        $this->assertCount(0, MaxCapacityCallLog::all());
    }
}

<?php

namespace Tests\Feature\Web\PhoneNumberSchedule;

use App\Models\Competition;
use App\Models\PhoneBookEntry;
use App\Models\PhoneLineSchedule;
use Carbon\Carbon;
use Tests\TestCase;

class PhoneNumberScheduleShowTest extends TestCase
{
    public function test_i_get_a_400()
    {
        $this->login();

        $this->get(route('web.phone-line-schedule.show', 4))->assertNotFound();
    }

    public function test_page_displays_as_expected()
    {
        $this->login();

        $phoneBook = PhoneBookEntry::factory()->count(5)->create();

        $schedule = PhoneLineSchedule::factory()->create([
            'action_at' => now()->subMinute(),
            'competition_phone_number'=> $phoneBook[1]->phone_number,
            'processed' => true,
        ]);

        $this->get(route('web.phone-line-schedule.show', $schedule->id))
            ->assertOk();
    }
}

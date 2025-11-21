<?php

namespace Tests\Feature\Web\PhoneNumberSchedule;

use App\Models\Competition;
use App\Models\PhoneBookEntry;
use App\Models\PhoneLineSchedule;
use Carbon\Carbon;
use Tests\TestCase;

class PhoneNumberScheduleIndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2004-04-30 09:10:00');

        $this->phoneBook = PhoneBookEntry::factory()->count(5)->create();

        $this->scheduledPhoneNumber = $this->phoneBook[0]->phone_number;

        $this->competitionOld = Competition::factory()->hasPhoneLines(1, ['phone_number' => $this->scheduledPhoneNumber])->create();

        $this->competitionNew = Competition::factory()->create();
    }

    public function test_i_get_a_400_when_the_number_does_not_exist_in_the_phone_book()
    {
        $this->login();

        $this->get(route('web.phone-line-schedule.index', '016041122334'))
            ->assertNotFound();
    }

    public function test_page_shows_non_processed_schedules()
    {
        $this->login();

        $schedule = PhoneLineSchedule::factory()->create(['competition_phone_number'=>$this->scheduledPhoneNumber]);

        $scheduleProcessed = PhoneLineSchedule::factory()->create([
            'action_at' => now()->subMinute(),
            'competition_phone_number'=> $this->phoneBook[1]->phone_number,
            'processed' => true,
        ]);

        $this->get(route('web.phone-line-schedule.index', $this->scheduledPhoneNumber))
            ->assertOk()
            ->assertSee($schedule->competition_phone_number)
            ->assertDontSee($scheduleProcessed->competition_phone_number);
    }

    public function test_page_shows_processed_schedules()
    {
        $this->login();

        $schedule = PhoneLineSchedule::factory()->create(['competition_phone_number'=>$this->scheduledPhoneNumber]);

        $scheduleProcessed = PhoneLineSchedule::factory()->create([
            'action_at' => now()->subMinute(),
            'competition_phone_number'=> $this->phoneBook[1]->phone_number,
            'processed' => true,
        ]);

        $this->get(route('web.phone-line-schedule.index', ['competitionPhoneNumber' => $this->phoneBook[1]->phone_number, 'processed' => true]))
            ->assertOk()
            ->assertDontSee($schedule->competition_phone_number)
            ->assertSee($scheduleProcessed->competition_phone_number);
    }


}

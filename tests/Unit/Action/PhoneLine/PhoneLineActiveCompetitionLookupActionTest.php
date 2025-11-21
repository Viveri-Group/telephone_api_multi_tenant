<?php

namespace Tests\Unit\Action\PhoneLine;

use App\Action\PhoneLine\PhoneLineActiveCompetitionLookupAction;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PhoneLineActiveCompetitionLookupActionTest extends TestCase
{
    public function test_it_only_returns_open_competitions_for_given_phone_number()
    {
        Carbon::setTestNow('2024-10-01 09:00:00');

        // Not active
        $competitionA = Competition::factory()->create([
            'start' => '2024-09-01 09:00:00',
            'end' => '2024-09-30 09:00:00',
        ]);
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionA->id,
            'phone_number' => '0800000001',
        ]);

        // Not active
        $competitionB = Competition::factory()->create([
            'start' => '2024-09-01 09:00:00',
            'end' => '2024-10-01 08:00:00',
        ]);
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionB->id,
            'phone_number' => '0800000002',
        ]);

        // Active
        $competitionC = Competition::factory()->create([
            'start' => '2024-09-16 09:10:00',
            'end' => '2024-10-01 10:00:00',
        ]);
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionC->id,
            'phone_number' => '0800000003',
        ]);

        // Call action only for competitionC's number
        $activePhoneLines = (new PhoneLineActiveCompetitionLookupAction())->handle('0800000003');

        $this->assertCount(1, $activePhoneLines);
        $this->assertEquals($competitionC->id, $activePhoneLines->first()->competition_id);
    }

    public function test_it_returns_empty_when_no_matching_phone_number()
    {
        $result = (new PhoneLineActiveCompetitionLookupAction())->handle('0800999999');

        $this->assertTrue($result->isEmpty());
    }

    public function test_it_includes_competition_starting_now()
    {
        Carbon::setTestNow('2024-10-01 09:00:00');

        $competition = Competition::factory()->create([
            'start' => '2024-10-01 09:00:00',
            'end' => '2024-10-02 09:00:00',
        ]);

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '0800123400',
        ]);

        $result = (new PhoneLineActiveCompetitionLookupAction())->handle('0800123400');

        $this->assertCount(1, $result);
    }

    public function test_it_includes_competition_ending_now()
    {
        Carbon::setTestNow('2024-10-01 09:00:00');

        $competition = Competition::factory()->create([
            'start' => '2024-09-01 09:00:00',
            'end' => '2024-10-01 09:00:00',
        ]);

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '0800123411',
        ]);

        $result = (new PhoneLineActiveCompetitionLookupAction())->handle('0800123411');

        $this->assertCount(1, $result);
    }

    public function test_it_ignores_non_matching_phone_lines()
    {
        Carbon::setTestNow('2024-10-01 09:00:00');

        $competition = Competition::factory()->create([
            'start' => '2024-09-01 09:00:00',
            'end' => '2024-10-10 09:00:00',
        ]);

        // This will be ignored
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '0800000000',
        ]);

        // This one should match
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '0800999999',
        ]);

        $result = (new PhoneLineActiveCompetitionLookupAction())->handle('0800999999');

        $this->assertCount(1, $result);
        $this->assertEquals('0800999999', $result->first()->phone_number);
    }

    public function test_it_excludes_competition_if_is_open_is_false()
    {
        Carbon::setTestNow('2024-10-01 09:00:00');

        $competition = Competition::factory()->create([
            'start' => '2024-09-01 09:00:00',
            'end' => '2024-10-01 08:59:59', // ends just before now
        ]);

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '0800888888',
        ]);

        $result = (new PhoneLineActiveCompetitionLookupAction())->handle('0800888888');

        $this->assertCount(0, $result);
    }
}

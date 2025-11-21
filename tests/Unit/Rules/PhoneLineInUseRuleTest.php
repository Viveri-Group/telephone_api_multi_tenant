<?php

namespace Tests\Unit\Rules;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PhoneLineInUseRuleTest extends TestCase
{
    public function test_it_fails_when_phone_number_is_used_by_another_competition()
    {
        $competitionA = Competition::factory()->create();
        $competitionB = Competition::factory()->create();

        // Used phone number
        $phoneNumber = '+441234567890';

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionA->id,
            'phone_number' => $phoneNumber,
        ]);

        $rule = new \App\Rules\PhoneLineInUseCheckRule($competitionB);

        $result = Validator::make(
            ['phone_number' => $phoneNumber],
            ['phone_number' => [$rule]]
        );

        $this->assertTrue($result->fails());
        $this->assertEquals(
            "Phone number ({$phoneNumber}) is already in use.",
            $result->errors()->first('phone_number')
        );
    }

    public function test_it_passes_when_phone_number_is_used_by_same_competition()
    {
        $competition = Competition::factory()->create();
        $phoneNumber = '+441234567890';

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => $phoneNumber,
        ]);

        $rule = new \App\Rules\PhoneLineInUseCheckRule($competition);

        $result = Validator::make(
            ['phone_number' => $phoneNumber],
            ['phone_number' => [$rule]]
        );

        $this->assertFalse($result->fails());
    }
}

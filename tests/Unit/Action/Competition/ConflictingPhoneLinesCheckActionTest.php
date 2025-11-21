<?php

namespace Tests\Unit\Action\Competition;

use App\Action\Competition\ConflictingPhoneLinesCheckAction;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Tests\TestCase;

class ConflictingPhoneLinesCheckActionTest extends testCase
{
    public function test_it_returns_competitions_linked_to_other_phone_numbers()
    {
        // Given: three competitions
        $competitionA = Competition::factory()->create();
        $competitionB = Competition::factory()->create();
        $competitionC = Competition::factory()->create();

        // And: phone lines uniquely linked to each competition
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionA->id,
            'phone_number' => '1234567890',
        ]);

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionB->id,
            'phone_number' => '2345678901',
        ]);

        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competitionC->id,
            'phone_number' => '3456789012',
        ]);

        // When: we call handle() with two phone numbers and exclude competitionB
        $action = new ConflictingPhoneLinesCheckAction(
            competitionId: $competitionB->id,
            phoneNumbers: ['1234567890', '2345678901', '3456789012'],
        );

        $result = $action->handle();

        // Then: the result should include A and C, but not B
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains(fn($comp) => $comp->id === $competitionA->id));
        $this->assertTrue($result->contains(fn($comp) => $comp->id === $competitionC->id));
        $this->assertFalse($result->contains(fn($comp) => $comp->id === $competitionB->id));
    }

}

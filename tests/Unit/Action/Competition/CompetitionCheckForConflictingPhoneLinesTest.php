<?php

namespace Tests\Unit\Action\Competition;

use App\Action\Competition\CompetitionCheckForConflictingPhoneLines;
use App\Action\Competition\ConflictingPhoneLinesCheckAction;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Mockery;
use Tests\TestCase;

class CompetitionCheckForConflictingPhoneLinesTest extends TestCase
{
    public function test_it_returns_false_when_there_are_no_conflicts()
    {
        $competition = Competition::factory()->create();
        CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '1234567890',
        ]);

        // No other competitions using that number
        $checker = new CompetitionCheckForConflictingPhoneLines(
            $competition,
        );

        $this->assertFalse($checker->handle());
    }

    public function test_it_returns_error_message_when_conflicts_exist()
    {
        $competition = Competition::factory()
            ->hasPhoneLines(1, ['phone_number' => '1234567890'])
            ->create();

        $conflictingCompetition = Competition::factory()->create();

        // Bind with closure to simulate makeWith
        app()->bind(ConflictingPhoneLinesCheckAction::class, function ($app, $params) use ($conflictingCompetition) {
            $mock = Mockery::mock(ConflictingPhoneLinesCheckAction::class);
            $mock->shouldReceive('handle')
                ->once()
                ->andReturn(collect([$conflictingCompetition]));

            return $mock;
        });

        $checker = new CompetitionCheckForConflictingPhoneLines(
            $competition,
        );

        $result = $checker->handle();

        $this->assertIsString($result);
        $this->assertStringContainsString((string) $conflictingCompetition->id, $result);
    }
}

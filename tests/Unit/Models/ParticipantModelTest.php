<?php

namespace Tests\Unit\Models;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\Participant;
use Tests\TestCase;

class ParticipantModelTest extends TestCase
{
    public function test_telephone_is_regularised_on_store()
    {
        $competition = Competition::factory()->has(CompetitionPhoneLine::factory(), 'phoneLines')->create();

        $participant = Participant::factory()->create([
            'competition_id' => $competition->id,
            'telephone' => '+44 1604 233 322'
        ]);

        $this->assertEquals('441604233322', $participant->telephone);
    }
}

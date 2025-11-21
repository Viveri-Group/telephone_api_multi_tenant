<?php

namespace Tests\Unit\Models;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Tests\TestCase;

class PhoneLineTest extends TestCase
{
    public function test_phone_number_is_regularised_on_store()
    {
        $competition = Competition::factory()->create();

        $participant = CompetitionPhoneLine::factory()->create([
            'competition_id' => $competition->id,
            'phone_number' => '+44 1604 233 322'
        ]);

        $this->assertEquals('441604233322', $participant->phone_number);
    }
}

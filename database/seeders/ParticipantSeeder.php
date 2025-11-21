<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Participant;
use App\Models\CompetitionPhoneLine;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = Competition::all();
        $phoneLines = CompetitionPhoneLine::all();

        //active competition
        Participant::factory()->create([
            'competition_id' => $competitions->get(0)->id,
            'telephone' => '441604111111',
        ]);

        Participant::factory()->create([
            'competition_id' => $competitions->get(0)->id,
            'telephone' => '441604222222',
        ]);

        //closed competition
        Participant::factory()->create([
            'competition_id' => $competitions->get(1)->id,
            'telephone' => '441604333333',
        ]);

        Participant::factory()->create([
            'competition_id' => $competitions->get(1)->id,
            'telephone' => '441060433333',
        ]);
    }
}

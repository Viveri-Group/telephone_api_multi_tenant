<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionWinnerAlt;
use App\Models\Participant;
use App\Models\CompetitionPhoneLine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionAltWinnerFactory extends Factory
{
    protected $model = CompetitionWinnerAlt::class;

    public function definition(): array
    {
        return [
            'participant_id' => Participant::factory(),
            'competition_id' => Competition::factory(),
            'date_from' => $this->faker->dateTime(),
            'date_to' => $this->faker->dateTime(),
            'phone_line_id' => CompetitionPhoneLine::factory(),
            'telephone' => $this->faker->numerify('############'),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

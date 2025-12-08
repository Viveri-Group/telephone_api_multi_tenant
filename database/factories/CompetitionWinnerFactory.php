<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Participant;
use App\Models\CompetitionPhoneLine;
use App\Models\CompetitionWinner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionWinnerFactory extends Factory
{
    protected $model = CompetitionWinner::class;

    public function definition(): array
    {
        return [
            'participant_id' => Participant::factory(),
            'competition_id' => Competition::factory(),
            'call_id' => $this->faker->numerify('######'),
            'number_of_entries' => $this->faker->randomDigit(),
            'round_hash' => 'round_hash_value',
            'competition_phone_number' => $this->faker->numerify('############'),
            'call_start' => now()->subDay(),
            'call_end' => now()->subDay()->addMinute(),
            'telephone' => $this->faker->numerify('############'),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

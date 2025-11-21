<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionPhoneLineFactory extends Factory
{
    protected $model = CompetitionPhoneLine::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'phone_number' => $this->faker->numerify('############'),
            'cost'=>'1.50',

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

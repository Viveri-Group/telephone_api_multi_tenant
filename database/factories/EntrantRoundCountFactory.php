<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\EntrantRoundCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntrantRoundCountFactory extends Factory
{
    protected $model = EntrantRoundCount::class;

    public function definition(): array
    {
        return [
            'hash' => $this->faker->sha256(),
            'competition_id' => Competition::factory(),
            'caller_number' => $this->faker->numerify('44##########'),
        ];
    }
}

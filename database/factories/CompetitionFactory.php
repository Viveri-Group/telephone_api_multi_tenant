<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'start' => Carbon::now(),
            'end' => Carbon::now()->days(5),
            'max_paid_entries' => 10,

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

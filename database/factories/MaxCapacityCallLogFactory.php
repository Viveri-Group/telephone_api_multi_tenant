<?php

namespace Database\Factories;

use App\Models\MaxCapacityCallLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MaxCapacityCallLogFactory extends Factory
{
    protected $model = MaxCapacityCallLog::class;

    public function definition(): array
    {
        return [
            'call_id' => $this->faker->randomNumber(),
            'allowed_capacity' => $this->faker->randomNumber(),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

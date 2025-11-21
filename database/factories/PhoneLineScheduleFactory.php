<?php

namespace Database\Factories;

use App\Models\PhoneLineSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhoneLineScheduleFactory extends Factory
{
    protected $model = PhoneLineSchedule::class;

    public function definition(): array
    {
        return [
            'author_id' => 1,
            'competition_id' => 1,
            'competition_phone_number' => $this->faker->numerify('44##########'),
            'action_at' => now(),
            'processed' => false,
            'success' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

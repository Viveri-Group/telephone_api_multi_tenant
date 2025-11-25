<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\FailedEntry;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FailedEntryFactory extends Factory
{
    protected $model = FailedEntry::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'competition_id' => Competition::factory(),
            'phone_number' => $this->faker->numerify('############'),
            'caller_phone_number' => $this->faker->numerify('############'),
            'reason' => $this->faker->word(),
            'call_start' => now(),
            'call_end' => now()->addSeconds(10),
            'call_id' => $this->faker->randomNumber(),
            'station_name' => $this->faker->word(),
            'round_start' => now(),
            'round_end' => now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

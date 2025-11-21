<?php

namespace Database\Factories;

use App\Models\PhoneBookEntry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhoneBookEntryFactory extends Factory
{
    protected $model = PhoneBookEntry::class;

    public function definition(): array
    {
        $costs = ['1.50', '2.00', '2.50'];

        return [
            'phone_number' => $this->faker->numerify('44##########'),
            'cost' => $costs[array_rand($costs)],

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

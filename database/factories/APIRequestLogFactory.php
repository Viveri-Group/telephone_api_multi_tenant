<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class APIRequestLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'request_type' => $this->faker->word(),
            'ip_address' => $this->faker->ipv4(),
            'request_headers' => $this->faker->words(),
            'request_input' => $this->faker->words(),
            'response_status' => $this->faker->word(),
            'response_data' => $this->faker->words(),
        ];
    }
}

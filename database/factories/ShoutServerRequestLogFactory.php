<?php

namespace Database\Factories;

use App\Models\ShoutServerRequestLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoutServerRequestLogFactory extends Factory
{
    protected $model = ShoutServerRequestLog::class;

    public function definition(): array
    {
        return [
            'identifier' => $this->faker->word(),
            'status_code' => $this->faker->numberBetween(200, 500),
            'url' => $this->faker->url(),
            'http_method' => array_rand(['GET', 'POST', 'DELETE']),
            'request_type' => $this->faker->word(),
            'request_input' => '{}',
            'response_class' => $this->faker->word(),
            'request' => '{}',
            'response' => '{}',
            'request_start' => now()->subMicroseconds(300),
            'request_end' => now(),
            'response_time' => 300
        ];
    }
}

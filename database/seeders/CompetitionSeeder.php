<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        Competition::factory()->create([
            'name' => 'Closed Competition',
            'start' => now()->subDays(2),
            'end' => now()->subDay(),
        ]);

        Competition::factory()->create([
            'name' => 'Open Competition',
            'start' => now()->subDay(),
            'end' => now()->addDays(7),
        ]);

        Competition::factory()->create([
            'name' => 'Future Competition',
            'start' => now()->addDays(60),
            'end' => now()->addDays(67),
        ]);
    }
}

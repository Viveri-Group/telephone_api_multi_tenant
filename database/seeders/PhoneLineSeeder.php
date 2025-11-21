<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Illuminate\Database\Seeder;

class PhoneLineSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = Competition::all();

        //active competition
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(0)->id, 'phone_number' => '448001111119', 'cost' => '1.50']);
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(0)->id, 'phone_number' => '448002222223', 'cost' => '2.50']);

        //closed competition
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(1)->id, 'phone_number' => '448003333334', 'cost' => '2.00']);
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(1)->id, 'phone_number' => '448004444448', 'cost' => '1.50']);

        //future competition
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(2)->id, 'phone_number' => '643529486214', 'cost' => '2.00']);
        CompetitionPhoneLine::factory()->create(['competition_id' => $competitions->get(2)->id, 'phone_number' => '203591696819', 'cost' => '5.50']);
    }
}

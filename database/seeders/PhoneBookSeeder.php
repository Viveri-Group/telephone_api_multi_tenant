<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\PhoneBookEntry;
use Illuminate\Database\Seeder;

class PhoneBookSeeder extends Seeder
{
    public function run(): void
    {
        $phoneBookEntry = [
             ['number'=>'448001111119','name'=>'FOO', 'cost'=>'1.50'],
             ['number'=>'448002222223','name'=>'BAR', 'cost'=>'2.50'],
             ['number'=>'448003333334','name'=>'BAZ', 'cost'=>'2.00'],
             ['number'=>'448004444448','name'=>'BAT', 'cost'=>'1.50'],
             ['number'=>'643529486214','name'=>'WOO', 'cost'=>'2.00'],
             ['number'=>'203591696819','name'=>'GEE', 'cost'=>'2.50'],
             ['number'=>'926458507126','name'=>'ROO', 'cost'=>'1.50'],
        ];

        foreach ($phoneBookEntry as $entry) {
            PhoneBookEntry::factory()->create([
                'phone_number' => $entry['number'],
                'name' => $entry['name'],
                'cost' => $entry['cost'],
            ]);
        }
    }
}

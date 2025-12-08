<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionDraw;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionDrawFactory extends Factory
{
    protected $model = CompetitionDraw::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::Factory(),
            'round_from' => '2020-01-01',
            'round_to' => '2020-01-08',
            'round_hash' => 'FOO',
            'drawn_by' => '999',

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

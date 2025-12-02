<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\FileDefault;
use Illuminate\Database\Seeder;

class FileDefaultSeeder extends Seeder
{
    public function run(): void
    {
        FileDefault::factory(['type' => 'INTRO', 'external_id' => 35, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CLI_READOUT_NOTICE', 'external_id' => 36, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'DTMF_MENU', 'external_id' => 37, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'DTMF_SUCCESS', 'external_id' => 38, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'DTMF_FAIL', 'external_id' => 39, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'COMPETITION_CLOSED', 'external_id' => 40, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'TOO_MANY_ENTRIES', 'external_id' => 41, 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
    }
}

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
        FileDefault::factory(['type' => 'FN1', 'external_id' => '59', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'FN2', 'external_id' => '60', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'FN3', 'external_id' => '61', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'FN4', 'external_id' => '62', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'FN5', 'external_id' => '63', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'FN6', 'external_id' => '64', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'PRE_EVENT', 'external_id' => '65', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'POST_EVENT', 'external_id' => '66', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CALL_COST_WARNING', 'external_id' => '67', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CAPPING_MESSAGE', 'external_id' => '68', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CALL_COST_WARNING_2_00', 'external_id' => '1', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CAPPING_MESSAGE_2_00', 'external_id' => '2', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CALL_COST_WARNING_2_50', 'external_id' => '3', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
        FileDefault::factory(['type' => 'CAPPING_MESSAGE_2_50', 'external_id' => '4', 'filename' => null, 'mime_type' => null, 'extension' => null])->create();
    }
}

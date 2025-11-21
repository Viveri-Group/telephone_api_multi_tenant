<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\FileUpload;
use Illuminate\Database\Seeder;

class FileUploadSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = Competition::all();

        //active competition
        FileUpload::factory()->create([
            'competition_id' => $competitions->get(0)->id,
            'filename' => 'foo.mp3',
            'mime_type' => 'audio/mp3',
            'extension' => 'mp3',
        ]);

        FileUpload::factory()->create([
            'competition_id' => $competitions->get(0)->id,
            'filename' => 'foo2.mp3',
            'mime_type' => 'audio/mp3',
            'extension' => 'mp3',
        ]);

        //closed competition
        FileUpload::factory()->create([
            'competition_id' => $competitions->get(1)->id,
            'filename' => 'bar.mp3',
            'mime_type' => 'audio/mp3',
            'extension' => 'mp3',
        ]);
    }
}

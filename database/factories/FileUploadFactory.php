<?php

namespace Database\Factories;

use App\Enums\CompetitionAudioType;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileUploadFactory extends Factory
{
    protected $model = FileUpload::class;

    public function definition(): array
    {
        $extension = $this->faker->randomElement(['mp3', 'wav', 'aac', 'wmf']);

        return [
            'external_id' => rand(15, 5000),
            'competition_id' => Competition::factory(),
            'competition_phone_line_id' => CompetitionPhoneLine::factory(),
            'type'=> $this->faker->randomElement(CompetitionAudioType::names()),
            'filename' => "{$this->faker->word()}.{$extension}",
            'name' => 'My File',
            'size' => 1000,
            'mime_type' => $this->faker->word(),
            'extension' => $extension,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

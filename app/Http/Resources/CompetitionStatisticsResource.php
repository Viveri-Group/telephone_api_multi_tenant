<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionStatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'competition-statistics',
            'attributes' => [
                'participant_count' => $this['participant_count'],
                'competition_name' => $this['competition_name'],
                'competition_id' => $this['competition_id'],
            ]
        ];
    }
}

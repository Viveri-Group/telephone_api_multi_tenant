<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CompetitionDrawsCollection extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'competition-draw',
            'attributes' => [
                'competition_id' => $this->competition_id,
                'competition_type' => $this->competition_type,
                'round_from' => $this->round_from,
                'round_to' => $this->round_to,
                'round_hash' => $this->round_hash,
                'drawn_by' => $this->drawn_by,
            ]
        ];
    }
}

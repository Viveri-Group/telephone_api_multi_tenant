<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WinnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing(['draw']);

        return [
            'type' => 'competition-winner',
            'id' => $this->id,
            'attributes' => [
                'participant_id' => $this->participant_id,
                'competition_id' => $this->competition_id,
                'call_id' => $this->call_id,
                'number_of_entries' => $this->number_of_entries,
                'round_hash' => $this->draw->round_hash,
                'round_from' => $this->draw->round_from,
                'round_to' => $this->draw->round_to,
                'phone_line_id' => $this->phone_line_id,
                'competition_phone_number' => $this->competition_phone_number,
                'telephone' => $this->telephone,
                'call_start' => $this->call_start,
                'call_end' => $this->call_end,
                'created_at' => $this->created_at,
            ],
        ];
    }
}

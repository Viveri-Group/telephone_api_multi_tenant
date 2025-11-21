<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WinnerAltResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'competition-alternate-winner',
            'id' => $this->id,
            'attributes' => [
                'participant_id' => $this->participant_id,
                'competition_id' => $this->competition_id,
                'call_id' => $this->call_id,
                'number_of_entries' => $this->number_of_entries,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'phone_line_id' => $this->phone_line_id,
                'competition_phone_number' => $this->competition_phone_number,
                'telephone' => $this->telephone,
                'call_start' => $this->call_start->format('Y-m-d H:i:s'),
                'call_end' => $this->call_end->format('Y-m-d H:i:s'),
                'created_at' => $this->created_at,
            ],
        ];
    }
}

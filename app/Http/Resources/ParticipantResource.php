<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'participant',
            'id' => $this->id,
            'attributes' => [
                'uuid' => $this->uuid,
                'organisation_id' => $this->organisation_id,
                'competition_id' => $this->competition_id,
                'call_id' => $this->call_id,
                'phone_line_id' => $this->competition_phone_line_id,
                'competition_phone_number' => $this->competition_phone_number,
                'caller_phone_number' => $this->telephone,
                'entry_status' => $this->entry_status ?? '',
                'is_free_entry' => $this->is_free_entry,
                'audio_file_number' => $this->audio_file_number,
                'call_start' => $this->call_start?->format('Y-m-d H:i:s'),
                'call_end' => $this->call_end?->format('Y-m-d H:i:s'),
                'created' => $this->created_at?->format('Y-m-d H:i:s'),
            ]
        ];
    }
}

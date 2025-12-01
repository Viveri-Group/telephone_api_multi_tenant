<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailedEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'failed-entry',
            'id' => $this->id,
            'attributes' => [
                'organisation_id' => $this->organisation_id,
                'competition_id' => $this->competition_id,
                'call_id' => $this->call_id,
                'competition_phone_number' => $this->phone_number,
                'caller_phone_number' => $this->caller_phone_number,
                'reason' => $this->reason,
                'call_start' => $this->call_start?->format('Y-m-d H:i:s'),
                'call_end' => $this->call_end?->format('Y-m-d H:i:s'),
            ]
        ];
    }
}

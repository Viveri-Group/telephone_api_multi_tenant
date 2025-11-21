<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveCallResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'active-call',
            'id' => $this->id,
            'attributes' => [
                'competition_id' => $this->competition_id,
                'call_id' => $this->call_id,
                'phone_number' => $this->phone_number,
                'caller_phone_number' => $this->caller_phone_number,
            ]
        ];
    }
}

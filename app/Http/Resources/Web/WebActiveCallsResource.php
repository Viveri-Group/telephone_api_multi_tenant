<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebActiveCallsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'competition_id' => $this->competition_id,
            'call_id' => $this->call_id,
            'phone_number' => $this->phone_number,
            'caller_phone_number' => $this->caller_phone_number,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}

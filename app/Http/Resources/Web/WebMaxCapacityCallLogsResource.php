<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebMaxCapacityCallLogsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'call_id' => $this->call_id,
            'allowed_capacity' => $this->allowed_capacity,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'phone-line',
            'id' => $this->id,
            'attributes' => [
                'competition_id' => $this->competition->id,
                'number' => $this->phone_number,
            ]
        ];
    }
}

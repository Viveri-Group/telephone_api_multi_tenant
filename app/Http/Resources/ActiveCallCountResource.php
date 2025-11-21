<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveCallCountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'active-call-count',
            'attributes' => [
                'count' => $this->resource,
            ]
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneBookLookupEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'phone-book-lookup-entry',
            'phone_line' => [
                'id' => $this->resource?->id,
                'number' => $this->resource?->phone_number,
            ],
            'competition' => [
                'id' => $this->resource?->competition?->id,
                'name' => $this->resource?->competition?->name,
                'start' => $this->resource?->competition?->start,
                'end' => $this->resource?->competition?->end,
            ],
        ];
    }
}

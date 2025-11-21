<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing(['phoneLines', 'winners', 'files', 'draws']);

        return [
            'type' => 'competition',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'is_open' => $this->isOpen,
                'start' => $this->start->toIso8601String(),
                'end' => $this->end->toIso8601String(),

                'max_paid_entries' => $this->max_paid_entries,
                'entries_warning' => $this->entries_warning ?? 0,
                'special_offer' => $this->special_offer,
            ],
            'relationships' => [
                'phone_lines' => PhoneLineChildResource::collection($this->phoneLines),
                'winner' => WinnerResource::collection($this->winners),
                'files' => FileUploadResource::collection($this->files),
                'draws' => CompetitionDrawsCollection::collection($this->draws)
            ]
        ];
    }
}

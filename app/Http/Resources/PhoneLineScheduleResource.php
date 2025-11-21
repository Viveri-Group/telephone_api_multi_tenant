<?php

namespace App\Http\Resources;

use App\Models\PhoneLineSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneLineScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'competition_phone_number' => $this->competition_phone_number,
            'competition_id' => $this->competition_id,
            'action_at' => $this->action_at,
            'processed' => $this->processed,
            'completed_at' => $this->completed_at,
            'success' => $this->success,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}

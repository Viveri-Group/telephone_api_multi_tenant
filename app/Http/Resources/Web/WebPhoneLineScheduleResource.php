<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebPhoneLineScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'phone-line-schedule',
            'id' => $this->id,
            'attributes' => [
                'author_id' => $this->author_id,
                'competition_phone_number' => $this->competition_phone_number,
                'competition_id' => $this->competition_id,
                'action_at' => $this->action_at,
                'processed' => $this->processed,
                'completed_at' => $this->completed_at,
                'success' => $this->success,
                'notes' => $this->notes,
                'created_at' => $this->created_at,
            ]
        ];
    }
}

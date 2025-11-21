<?php

namespace App\Http\Resources;

use App\Action\File\GetCompetitionAudioAction;
use App\Action\File\GetCompetitionDefaultAudioAction;
use App\Enums\CompetitionAudioType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionCapacityCheckWithoutActivePhoneLineButDefaultAudioResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $audio = (new GetCompetitionDefaultAudioAction(CompetitionAudioType::names()))->handle();

        if(array_key_exists('DTMF_SUCCESS_SMS', $audio)) {
            unset($audio['DTMF_SUCCESS_SMS']);
        }

        return [
            'competition_id' => $this->resource['competition_id'],
            'status' => $this->resource['status'],
            'active_call_id' => $this->resource['active_call_id'],
            ...$audio,
        ];
    }
}

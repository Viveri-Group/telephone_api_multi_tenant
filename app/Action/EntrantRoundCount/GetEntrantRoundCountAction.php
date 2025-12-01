<?php

namespace App\Action\EntrantRoundCount;

use App\DTO\ActiveCall\ActiveCallDTO;
use App\Models\ActiveCall;
use App\Models\EntrantRoundCount;

class GetEntrantRoundCountAction
{
    public function handle(ActiveCall|ActiveCallDTO $activeCall): int
    {
        $entrant = EntrantRoundCount::where('hash', hash('xxh128', "{$activeCall->round_start} {$activeCall->competition_id} {$activeCall->caller_phone_number}"))->first();

        if($entrant){
            return $entrant->total_entry_count;
        }

        return 0;
    }
}

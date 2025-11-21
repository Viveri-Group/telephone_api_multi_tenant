<?php

namespace App\Action\ClearDown;

use App\Models\ActiveCall;
use App\Models\ActiveCallOrphan;
use App\Models\APIRequestLog;
use App\Models\EntrantRoundCount;
use App\Models\FailedEntry;
use App\Models\MaxCapacityCallLog;

class LogClearDownAction
{
    public function handle(): void
    {
        ActiveCall::query()->truncate();
        ActiveCallOrphan::query()->truncate();
        APIRequestLog::query()->truncate();
        EntrantRoundCount::query()->truncate();
        FailedEntry::query()->truncate();
        MaxCapacityCallLog::query()->truncate();
    }
}

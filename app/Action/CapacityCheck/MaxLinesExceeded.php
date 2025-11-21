<?php

namespace App\Action\CapacityCheck;

class MaxLinesExceeded
{
    public function handle(int $currentActiveCalls): bool
    {
        if(!config('system.ENFORCE_MAX_NUMBER_OF_LINES')) {
            return false;
        }

        if(config('system.MAX_NUMBER_OF_LINES') < 0){
            return false;
        }

        return $currentActiveCalls >= config('system.MAX_NUMBER_OF_LINES');
    }
}

<?php

namespace App\Action\CapacityCheck;

use App\Action\Organisation\GetOrganisationAction;

class MaxLinesExceeded
{
    public function handle(int $organisationId, int $currentActiveCalls): bool
    {
        $org = (new GetOrganisationAction())->handle($organisationId);

        if(!$org['max_number_of_lines']) {
            return false;
        }

        if($org['max_number_of_lines'] < 0){
            return false;
        }

        return $currentActiveCalls >= $org['max_number_of_lines'];
    }
}

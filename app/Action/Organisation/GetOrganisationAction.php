<?php

namespace App\Action\Organisation;

class GetOrganisationAction
{
    public function handle(int $organisationId)
    {
        $organisations = (new GetOrganisationsAction())->handle();

        return $organisations[$organisationId];
    }
}

<?php

namespace App\DTO\Competition;

class CompetitionPreCheckRequestDTO
{
    public function __construct(
        public string $callerPhoneNumber,
        public string $competitionPhoneNumber,
        public ?string $callID,
        public ?int $cliPresentation,
    )
    {
    }
}

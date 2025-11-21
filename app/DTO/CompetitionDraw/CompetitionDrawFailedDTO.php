<?php

namespace App\DTO\CompetitionDraw;

class CompetitionDrawFailedDTO
{
    public function __construct(
        public string $message,
        public int $code,
    )
    {
    }
}

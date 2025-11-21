<?php

namespace App\DTO\CompetitionDraw;

class CompetitionDrawSuccessDTO
{
    public function __construct(
        public string $message,
        public int $code,
        public array $data,
    )
    {
    }
}

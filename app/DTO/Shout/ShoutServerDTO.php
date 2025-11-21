<?php

namespace App\DTO\Shout;

class ShoutServerDTO
{
    public function __construct(
        public string $identifier,
        public string $ip_address,
        public string $username,
        public string $password,
    )
    {
    }
}

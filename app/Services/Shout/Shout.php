<?php

namespace App\Services\Shout;

class Shout
{
    public function __construct(
        protected string $identifier,
        protected string $ipAddress,
        protected string $username,
        protected string $password,
        protected string $environment,
    )
    {
    }

    public function getBaseUrl(): string
    {
        return "http://{$this->ipAddress}/filemanager/api/global";
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

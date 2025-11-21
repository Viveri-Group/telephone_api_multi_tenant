<?php

namespace App\Http\Middleware;

class APIRequestContainer
{
    public function __construct(protected ?string $tracingId = null)
    {
    }

    public function setTracingId(string $tracingId)
    {
        $this->tracingId = $tracingId;

        return $this;
    }

    public function getTracingId(): ?string
    {
        return $this->tracingId;
    }
}

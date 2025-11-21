<?php

namespace App\Services\Shout\Response;

use Illuminate\Support\Collection;

class ShoutError extends Collection
{
    public function __construct(protected ?array $response)
    {
        parent::__construct($response);

        if ($this->response === null) {
            $this->response = [];
        }
    }

    public function isSuccessful(): bool
    {
        return false;
    }
}

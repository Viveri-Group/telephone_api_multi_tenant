<?php

namespace App\Domain\ErrorHandling\Types;

use App\Domain\ErrorHandling\AbstractErrorHandler;
use Exception;
use Illuminate\Support\Str;

class AuthenticationExceptionHandler extends AbstractErrorHandler
{
    public function handle(Exception $exception): array
    {
        return [
            'status' => 401,
            'message' => $this->sanitize($exception->getMessage()),
        ];
    }
}

<?php

namespace App\Domain\ErrorHandling\Types;

use App\Domain\ErrorHandling\AbstractErrorHandler;
use Throwable;

class DefaultExceptionHandler extends AbstractErrorHandler
{
    public function handle(Throwable $exception): array
    {
        return [
            'status' => $this->getStatusCode($exception),
            'message' => $exception->getMessage() ?: 'An unexpected error occurred.',
        ];
    }
}

<?php

namespace App\Domain\ErrorHandling\Types;

use App\Domain\ErrorHandling\AbstractErrorHandler;
use Exception;
use Illuminate\Support\Str;

class NotFoundHttpExceptionHandler extends AbstractErrorHandler
{
    public function handle(Exception $exception): array
    {
        return [
            'status' => 404,
            'message' => $this->sanitize($exception->getMessage()),
        ];
    }
}

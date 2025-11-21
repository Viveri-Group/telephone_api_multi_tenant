<?php

namespace App\Domain\ErrorHandling\Types;

use App\Domain\ErrorHandling\AbstractErrorHandler;
use Exception;

class ValidationExceptionHandler extends AbstractErrorHandler
{
    public function handle(Exception $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $key => $value) {
            foreach ($value as $message) {
                $errors['data'][] = [
                    'message' => $message,
                    'source' => $key
                ];
            }
        }

        $errors['message'] = $exception->getMessage();
        $errors['status'] = 422;

        return $errors;
    }
}

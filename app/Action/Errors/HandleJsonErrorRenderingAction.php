<?php

namespace App\Action\Errors;

use App\Domain\ErrorHandling\Types\DefaultExceptionHandler;
use App\Domain\ErrorHandling\Types\NotFoundHttpExceptionHandler;
use App\Domain\ErrorHandling\Types\UnauthorizedExceptionHandler;
use App\Domain\ErrorHandling\Types\ValidationExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TypeError;

class HandleJsonErrorRenderingAction
{
    public function handle(Throwable $exception, Request $request): ?JsonResponse
    {
        if ($request->expectsJson()) {
            $errorData = $this->formatErrorsCheck($exception);

            $errorResponse = [
                'message' => $errorData['message'],
            ];

            if (array_key_exists('data', $errorData)) {
                $errorResponse['data'] = $errorData['data'];
            }

            return response()->json($errorResponse, $errorData['status']);
        }

        return null;
    }

    protected function formatErrorsCheck(Throwable $exception): array
    {
        if ($exception instanceof TypeError) {
            return [
                'status' => 410,
                'message' => !app()->environment('production') ? $exception->getMessage() : 'An error occurred',
            ];
        }

        return match (get_class($exception)) {
            ValidationException::class => app(ValidationExceptionHandler::class)->handle($exception),
            NotFoundHttpException::class => app(NotFoundHttpExceptionHandler::class)->handle($exception),
            UnauthorizedException::class, AuthenticationException::class => app(UnauthorizedExceptionHandler::class)->handle($exception),
            default => app(DefaultExceptionHandler::class)->handle($exception)
        };
    }
}

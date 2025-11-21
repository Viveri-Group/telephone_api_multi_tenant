<?php

namespace App\Domain\ErrorHandling;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class AbstractErrorHandler
{
    abstract public function handle(Exception $exception);

    public function getStatusCode(Throwable $exception)
    {
        return ($exception instanceof HttpResponseException || $exception instanceof HttpException)
            ? $exception->getStatusCode()
            : 500;
    }

    protected function sanitize(string $input): string
    {
        $focusString = '[' . Str::between($input, '[', ']') . ']';

        preg_match('/([^\\\[\]]+)]$/', $focusString, $matches);

        return Str::replaceFirst($focusString, $matches[1], $input);
    }
}

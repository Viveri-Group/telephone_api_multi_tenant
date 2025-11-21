<?php

namespace App\Services\Shout\Requests;

use App\DTO\Shout\ShoutServerDTO;
use App\Models\ShoutServerRequestLog;
use App\Services\Shout\Response\ShoutBadRequest;
use App\Services\Shout\Response\ShoutDependencyError;
use App\Services\Shout\Response\ShoutForbidden;
use App\Services\Shout\Response\ShoutInternalServerError;
use App\Services\Shout\Response\ShoutNotFoundError;
use App\Services\Shout\Response\ShoutTooManyRequests;
use App\Services\Shout\Response\ShoutUnauthorised;
use App\Services\Shout\Response\ShoutUnprocessable;
use App\Services\Shout\Response\Response as ShoutResponse;
use App\Services\Shout\Response\ShoutError;
use App\Services\Shout\Shout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class BaseRequest
{
    protected Shout $server;

    abstract protected function getUri(): string;

    public function setServer(ShoutServerDTO $serverDTO): void
    {
        $this->server = App::makeWith(Shout::class, [
            'identifier' => $serverDTO->identifier,
            'ipAddress' => $serverDTO->ip_address,
            'username' => $serverDTO->username,
            'password' => $serverDTO->password,
            'environment' => config('system.SHOUT_SERVER_ACTIVE_ENVIRONMENT')
        ]);
    }

    protected function getHeaders(): array
    {
        return [];
    }

    protected function getParams(): array
    {
        return [];
    }

    protected function getFile(): ?array
    {
        return null;
    }

    protected function getRequestUrl(): string
    {
        return $this->server->getBaseUrl() . Str::of($this->getUri());
    }

    protected function getDefaultConnectTimeout(): int
    {
        return 8;
    }

    protected function getDefaultTimeout(): int
    {
        return 40;
    }

    protected function getRetryTimes(): int
    {
        return 3;
    }

    protected function getHttpMethod(): string
    {
        return 'get';
    }

    protected function onError(ShoutError $response)
    {
        return $response;
    }

    public function handle()
    {
        $attempts = 1;
        $startTime = Carbon::now();

        /** @var Response $response */
        $response = Http::acceptJson()
            ->withHeaders($this->getHeaders())
            ->connectTimeout($this->getDefaultConnectTimeout())
            ->timeout($this->getDefaultTimeout())
            ->withBasicAuth($this->server->getUsername(), $this->server->getPassword());

        if($file = $this->getFile()) {
            $response = $response->asMultipart();
            $response = $response->attach($file['key'], $file['contents'], $file['filename']);
        }

        $response = $response->retry($this->getRetryTimes(), 2000, function ($exception) use (&$attempts) {
                if ($this->disableRetryAttempts() || app()->runningUnitTests()) {
                    return false;
                }

                if ($exception instanceof ConnectionException || Str::startsWith($exception->response->status(), 5)) {
                    $attempts++;

                    return true;
                }

                return false;
            }, throw: false)
            ->{$this->getHttpMethod()}($this->getRequestUrl(), $this->getParams());

        if ($response->successful()) {
            $this->logRequest(ShoutResponse::class, $response, $attempts, $startTime);

            return (new ShoutResponse($response->json()))->recursive();
        }

        $error = $response->json()['message'] ?? ['Unknown Error'];

        $errorReport = (match ($response->status()) {
            SymfonyResponse::HTTP_FORBIDDEN => (new ShoutForbidden($error)),
            SymfonyResponse::HTTP_FAILED_DEPENDENCY => (new ShoutDependencyError($error)),
            SymfonyResponse::HTTP_BAD_REQUEST => (new ShoutBadRequest($error)),
            SymfonyResponse::HTTP_UNAUTHORIZED => (new ShoutUnauthorised($error)),
            SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR => (new ShoutInternalServerError($error)),
            SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY => (new ShoutUnprocessable($error)),
            SymfonyResponse::HTTP_TOO_MANY_REQUESTS => (new ShoutTooManyRequests($error)),
            SymfonyResponse::HTTP_NOT_FOUND => (new ShoutNotFoundError($error)),
            default => (new ShoutError($error)),
        })->recursive();

        $this->logRequest($errorReport::class, $response, $attempts, $startTime);

        return $this->onError($errorReport);
    }

    private function logRequest($responseClassName, Response $response, int $attempts, Carbon $startTime)
    {
        $endTime = Carbon::now();

        $responseData = $response->json();

        $tracingId = $responseData['meta']['tracing_id'] ?? '';

        if ($response->failed()) {
            $tracingId = $responseData['error']['tracing_id'] ?? '';
        }

        ShoutServerRequestLog::create([
            'identifier' => $this->server->getIdentifier(),
            'status_code' => $response->getStatusCode(),
            'url' => $this->getRequestUrl(),
            'http_method' => $this->getHttpMethod(),
            'request_type' => get_class($this),
            'request_input' => $this->getClassProperties(),
            'response_class' => $responseClassName,
            'request' => $this->getParams(),
            'response' => $responseData,
            'attempts' => $attempts,
            'request_start' => $startTime,
            'request_end' => $endTime,
            'response_time' => (int)$startTime->diffInRealMilliseconds($endTime, absolute: true),
            'tracing_id' => $tracingId,
        ]);
    }

    protected function disableRetryAttempts(): bool
    {
        return false;
    }

    private function getClassProperties()
    {
        $self = new ReflectionClass($this);
        $params = [];

        foreach ($self->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            $prop = $this->{$property->getName()};

            if ($prop instanceof Model) {
                $params[$property->getName()] = $prop->getKey();

                continue;
            }

            $params[$property->getName()] = $prop;
        }

        return $params;
    }
}

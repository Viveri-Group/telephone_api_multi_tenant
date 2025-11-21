<?php

namespace App\Http\Middleware;

use App\Action\Helpers\RegexIDInArrayAction;
use App\Enums\QueuePriority;
use App\Jobs\CreateAPIRequestLogJob;
use App\Jobs\UpdateAPIRequestLogJob;
use App\Models\APIRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogAPIRequestMiddleware
{
    protected array $excludedHeaders = [
        'authorization',
        'cookie',
        'content-type',
        'x-xsrf-token',
    ];

    protected array $excludeResponseDataRequestTypes = [
        '/api/download/competition/{id}/entrants'
    ];

    protected string $requestType;

    public function handle(Request $request, Closure $next): Response
    {
        $this->requestType = $request->getRequestUri();

        return match(config('system.LOG_API_REQUEST_USING_QUEUE')){
            true => $this->putJobOnQueue($request, $next),
            false => $this->writeDirectToDB($request, $next)
        };
    }

    protected function putJobOnQueue(Request $request, Closure $next)
    {
        $requestStart = now();

        $apiRequestLogUuid = Str::uuid()->toString();

        $createLogJob = new CreateAPIRequestLogJob(
            $request->user()->id,
            $apiRequestLogUuid,
            $request->ip(),
            $request->getRequestUri(),
            $this->convertRequestData($request),
            $this->getHeaders($request),
            $requestStart
        );

        app(APIRequestContainer::class)->setTracingId($apiRequestLogUuid);

        $response = $next($request);

        $requestEnd = now();

        dispatch($createLogJob->onQueue(QueuePriority::Low->value))->chain([
            (new UpdateAPIRequestLogJob(
                $apiRequestLogUuid,
                $this->getResponseData($response),
                $response->getStatusCode(),
                (int) round($requestEnd->diffInMilliseconds($requestStart, true)),
            ))->onQueue(QueuePriority::Low->value)
        ]);

        $response->headers->set('X-Tracing-ID', $apiRequestLogUuid);

        return $response;
    }

    protected function writeDirectToDB(Request $request, Closure $next)
    {
        $requestStart = now();

        $apiRequestLogUuid = Str::uuid()->toString();

        $apiRequestLog = APIRequestLog::create([
            'uuid' => $apiRequestLogUuid,
            'call_id' => $request->input('call_id') ?? null,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'request_type' => $this->requestType,
            'request_input' => $this->convertRequestData($request),
            'request_headers' => $this->getHeaders($request),
            'response_data' => '',
            'response_status' => '',
            'created_at' => $requestStart
        ]);

        app(APIRequestContainer::class)->setTracingId($apiRequestLogUuid);

        $response = $next($request);

        $requestEnd = now();

        $apiRequestLog?->update([
            'response_data' => $this->getResponseData($response),
            'response_status' => $response->getStatusCode(),
            'duration' => (int) round($requestEnd->diffInMilliseconds($requestStart, true)),
        ]);

        $response->headers->set('X-Tracing-ID', $apiRequestLogUuid);

        return $response;
    }

    protected function getResponseData($response)
    {
        if((new RegexIDInArrayAction())->handle($this->excludeResponseDataRequestTypes, $this->requestType)){
            return '';
        }

        return json_decode($response->getContent()) ?? $response->getContent();
    }

    protected function convertRequestData(Request $request): array
    {
        $data = [];

        collect($request->all())->each(function ($value, $key) use (&$data) {
            if(is_string($value) || is_numeric($value)){
                $data[$key] = $value;
            }

            if($value instanceof UploadedFile){
                $data[$key] = [
                    'name' => $value->getClientOriginalName(),
                    'mimeType' => $value->getClientMimeType(),
                    'size' => $value->getSize(),
                    'originalPath' => $value->getPath(),
                ];
            }
        });

        return $data;
    }

    protected function getHeaders(Request $request): array
    {
        return collect($request->headers)
            ->except($this->excludedHeaders)
            ->toArray();
    }
}

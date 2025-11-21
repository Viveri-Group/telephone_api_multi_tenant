<?php

namespace App\Action\Competition;

use App\Exceptions\CallerExceededMaxEntriesHTTPException;
use App\Exceptions\CompetitionClosedHTTPException;
use App\Exceptions\NoActiveCompetitionButCompetitionNumberKnownHTTPException;
use App\Http\Resources\CompetitionCapacityCheckResource;
use App\Http\Resources\CompetitionCapacityCheckWithActivePhoneLineResource;
use App\Http\Resources\CompetitionCapacityCheckWithoutActivePhoneLineButDefaultAudioResource;
use App\Models\Competition;
use Throwable;

class CapacityCheckExceptionHandlerAction
{
    public function handle(Throwable $exception): array
    {
        $responseParameters = $exception->getParameters();

        $httpResponseCode = $exception->getStatusCode();

        $status = $responseParameters['status'];

        $errorType = get_class($exception);

        $response = match ($errorType) {
            NoActiveCompetitionButCompetitionNumberKnownHTTPException::class => (new CompetitionCapacityCheckWithoutActivePhoneLineButDefaultAudioResource($responseParameters)),
            CompetitionClosedHTTPException::class => (new CompetitionCapacityCheckWithActivePhoneLineResource($responseParameters['active_phone_line'], $responseParameters)),
            default => (new CompetitionCapacityCheckResource(Competition::find($responseParameters['competition_id']), $responseParameters)),
        };

        return [
            $httpResponseCode,
            $status,
            $errorType,
            $response,
        ];
    }
}

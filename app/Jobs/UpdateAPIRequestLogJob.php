<?php

namespace App\Jobs;

use App\Models\APIRequestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use stdClass;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAPIRequestLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $uuid,
        public false|string|stdClass $responseData,
        public string $responseStatusCode,
        public int $duration
    )
    {
    }

    public function handle(): void
    {
        $apiRequestLog = ApiRequestLog::where('uuid', $this->uuid)->first();

        $apiRequestLog?->update([
            'response_data' => $this->responseData,
            'response_status' => $this->responseStatusCode,
            'duration' => $this->duration,
        ]);
    }

    public function tags(): array
    {
        return ['Update API Request Log'];
    }
}

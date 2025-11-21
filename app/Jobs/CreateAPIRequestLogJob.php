<?php

namespace App\Jobs;

use App\Models\APIRequestLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CreateAPIRequestLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $userId,
        public string $uuid,
        public string $ipAddress,
        public string $requestURI,
        public array $requestInput,
        public array $requestHeaders,
        public Carbon $created
    )
    {
    }

    public function handle(): APIRequestLog
    {
        return APIRequestLog::create([
            'uuid' => $this->uuid,
            'user_id' => $this->userId,
            'ip_address' => $this->ipAddress,
            'request_type' => $this->requestURI,
            'request_input' => $this->requestInput,
            'request_headers' => $this->requestHeaders,
            'response_data' => '',
            'response_status' => '',
            'created_at' => $this->created
        ]);
    }

    public function tags(): array
    {
        return ['Create API Request Log'];
    }
}

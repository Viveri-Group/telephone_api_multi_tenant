<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebApiRequestLogsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing(['user']);

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'user' => new UserResource($this->user),
            'duration' => $this->duration,
            'request_type' => $this->request_type,
            'request_headers' => $this->request_headers,
            'request_input' => $this->request_input,
            'response_status' => $this->response_status,
            'response_data' => $this->response_data,
            'created_at' => $this->created_at,
        ];
    }
}

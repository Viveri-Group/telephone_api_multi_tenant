<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebShoutRequestLogsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'status_code' => $this->status_code,
            'url' => $this->url,
            'http_method' => $this->http_method,
            'request_input' => $this->request_input,
            'request' => $this->request,
            'request_type' => $this->request_type,
            'request_start' => $this->request_start,
            'request_end' => $this->request_end,
            'response' => $this->response,
            'response_class' => $this->response_class,
            'response_time' => $this->response_time,
            'attempts' => $this->attempts,
            'created_at' => $this->created_at,
        ];
    }
}

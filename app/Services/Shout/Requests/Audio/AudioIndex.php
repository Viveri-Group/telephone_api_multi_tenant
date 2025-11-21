<?php

namespace App\Services\Shout\Requests\Audio;

use App\Services\Shout\Requests\BaseRequest;

class AudioIndex extends BaseRequest
{
    public function __construct(public array $params)
    {
    }

    public function getParams(): array
    {
        return $this->params;
    }

    protected function getHttpMethod(): string
    {
        return 'get';
    }

    protected function getUri(): string
    {
        return '';
    }
}

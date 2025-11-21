<?php

namespace App\Services\Shout\Requests\Audio;

use App\Models\FileUpload;
use App\Services\Shout\Requests\BaseRequest;

class AudioDelete extends BaseRequest
{
    public function __construct(public FileUpload $fileUpload)
    {
    }

    public function getParams(): array
    {
        return [];
    }

    protected function getHttpMethod(): string
    {
        return 'delete';
    }

    protected function getUri(): string
    {
        return "/{$this->fileUpload->external_id}";
    }
}

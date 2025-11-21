<?php

namespace App\Services\Shout\Requests\Audio;

use App\Models\FileUpload;
use App\Services\Shout\Requests\BaseRequest;
use Illuminate\Support\Facades\Storage;

class AudioStore extends BaseRequest
{
    public function __construct(public FileUpload $fileUpload, public array $params)
    {
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getFile(): ?array
    {
        return [
            'filename' => $this->fileUpload->filename,
            'contents' => Storage::disk('local')->get(FileUpload::LOCAL_BASE . $this->fileUpload->filename),
            'key' => 'file',
        ];
    }

    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    protected function getUri(): string
    {
        return '';
    }
}

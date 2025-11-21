<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileUploadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'file',
            'id' => $this->id,
            'attributes' => [
                'external_id' => $this->external_id,
                'filename' => $this->filename,
                'size' => $this->size,
                'mime_type' => $this->mime_type,
                'extension' => $this->extension,
                'type' => $this->type,
                'shout_name' => $this->name,
                'created_at' => $this->created_at
            ]
        ];
    }
}

<?php

namespace App\Action\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GetFileNameAction
{
    public function handle(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        return $originalFilename . '_' . Str::random(10) . '.' . $extension;
    }
}

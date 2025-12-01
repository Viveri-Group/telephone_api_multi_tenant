<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileUpload extends Model
{
    use HasFactory;

    const LOCAL_BASE = 'uploads/';

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}

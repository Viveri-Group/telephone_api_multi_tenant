<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class APIRequestLog extends Model
{
    use HasFactory, Prunable;

    protected $table = 'api_request_logs';

    protected function casts(): array
    {
        return [
            'request_headers' => 'json',
            'request_input' => 'json',
            'response_data' => 'json',
            'response_at' => 'datetime'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}

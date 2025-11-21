<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ShoutServerRequestLog extends Model
{
    use HasFactory, Prunable;

    protected function casts(): array
    {
        return [
            'request_input' => 'json',
            'request' => 'json',
            'response' => 'json',
            'request_start' => 'datetime',
            'request_end' => 'datetime',
        ];
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}

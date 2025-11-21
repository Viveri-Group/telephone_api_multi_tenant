<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ActiveCallOrphan extends Model
{
    use HasFactory, Prunable;

    protected $casts = [
        'original_call_time' => 'datetime'
    ];

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}

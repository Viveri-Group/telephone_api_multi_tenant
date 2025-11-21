<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class MaxCapacityCallLog extends Model
{
    use HasFactory, Prunable;

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}

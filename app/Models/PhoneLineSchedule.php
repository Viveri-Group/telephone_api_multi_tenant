<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneLineSchedule extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'action_at' => 'datetime',
            'completed_at' => 'datetime',
            'processed' => 'bool',
            'success' => 'bool',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $casts = [
        'call_start' => 'datetime',
        'call_end' => 'datetime',
        'round_start' => 'datetime',
        'round_end' => 'datetime',
        'is_free_entry' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Participant $participant) {
            if (empty($model->uuid)) {
                $participant->uuid = (string)Str::uuid();
            }
        });
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    protected function telephone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::replace(['+', ' '], '', $value),
        );
    }
}

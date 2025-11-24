<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Competition extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function draws(): HasMany
    {
        return $this->hasMany(CompetitionDraw::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(FileUpload::class);
    }

    public function failedEntries(): HasMany
    {
        return $this->hasMany(FailedEntry::class);
    }

    public function phoneLines(): HasMany
    {
        return $this->hasMany(CompetitionPhoneLine::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(CompetitionWinner::class);
    }

    public function isOpen(): Attribute
    {
        return new Attribute(fn() => now()->isBetween($this->start, $this->end));
    }

    public function isPreOpen(): Attribute
    {
        return new Attribute(fn() => now()->lt($this->start));
    }

    public function isClosed(): Attribute
    {
        return new Attribute(fn() => now()->gt($this->end));
    }

    public function isFinished(): Attribute
    {
        return new Attribute(fn() => Carbon::parse($this->end)->isPast());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitionDraw extends Model
{
    use HasFactory;

    public function competition(): HasOne
    {
        return $this->hasOne(Competition::class, 'id', 'competition_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(CompetitionWinner::class, 'round_hash', 'round_hash');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'competition_draw_id', 'id');
    }
}

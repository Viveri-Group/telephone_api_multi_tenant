<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitionWinner extends Model
{
    use HasFactory;

    public function draw(): HasOne
    {
        return $this->hasOne(CompetitionDraw::class, 'round_hash', 'round_hash');
    }
}

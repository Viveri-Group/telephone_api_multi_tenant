<?php

namespace App\Models;

use App\Action\PhoneLine\PhoneNumberCleanupAction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionPhoneLine extends Model
{
    use SoftDeletes, HasFactory;

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(FileUpload::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => (new PhoneNumberCleanupAction())->handle($value),
        );
    }
}

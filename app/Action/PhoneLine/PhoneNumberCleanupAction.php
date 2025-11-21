<?php

namespace App\Action\PhoneLine;

use Illuminate\Support\Str;

class PhoneNumberCleanupAction
{
    public function handle(string $phoneNumber): string
    {
        return Str::replace(['+', ' '], '', $phoneNumber);
    }
}

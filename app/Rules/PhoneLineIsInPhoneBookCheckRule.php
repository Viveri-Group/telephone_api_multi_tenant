<?php

namespace App\Rules;

use App\Action\PhoneLine\PhoneNumberCleanupAction;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\PhoneBookEntry;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneLineIsInPhoneBookCheckRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumber = (new PhoneNumberCleanupAction())->handle($value);

        if(!PhoneBookEntry::where('phone_number', $phoneNumber)->exists()) {
            $fail("Phone number does not exist in phone book.");
        }
    }
}

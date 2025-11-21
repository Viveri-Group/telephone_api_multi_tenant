<?php

namespace App\Rules;

use App\Action\PhoneLine\PhoneNumberCleanupAction;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneLineInUseCheckRule implements ValidationRule
{
    public function __construct(public Competition $competition)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumber = (new PhoneNumberCleanupAction())->handle($value);

        $phoneLine = CompetitionPhoneLine::where('phone_number', $phoneNumber)
            ->where('competition_id', '<>', $this->competition->id)
            ->first();

        if($phoneLine){
            $fail("Phone number ({$value}) is already in use.");
        }
    }
}

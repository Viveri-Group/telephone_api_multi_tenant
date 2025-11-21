<?php

namespace App\Rules;

use App\Action\PhoneLine\PhoneNumberCleanupAction;
use App\Models\Competition;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePhoneLineToCompetitionCheckRule implements ValidationRule
{
    public function __construct(public Competition $competition)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumber = (new PhoneNumberCleanupAction())->handle($value);

        $existingPhoneLineCount = Competition::query()
            ->where('id', $this->competition->id)
            ->withWhereHas('phoneLines', function ($query) use ($phoneNumber) {
                $query->where('phone_number', $phoneNumber);
            })
            ->count();

        if ($existingPhoneLineCount > 0) {
            $fail("The phone line {$value} is already associated with this competition.");
        }
    }
}

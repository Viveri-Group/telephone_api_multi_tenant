<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BooleanOrStringBooleanCheckRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_bool($value) && !in_array($value, ['true', 'false'], true)) {
            $fail($attribute . ' must be a boolean or the strings "true" or "false".');
        }
    }
}

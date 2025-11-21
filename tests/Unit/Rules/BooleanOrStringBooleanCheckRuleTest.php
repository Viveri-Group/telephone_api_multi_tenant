<?php

namespace Tests\Unit\Rules;

use App\Rules\BooleanOrStringBooleanCheckRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class BooleanOrStringBooleanCheckRuleTest extends TestCase
{
    public function test_it_validates_boolean_or_string_boolean_successfully()
    {
        $validValues = [true,false,'true','false'];

        foreach ($validValues as $value) {
            $validator = Validator::make(
                ['input_key' => $value],
                ['input_key' => [new BooleanOrStringBooleanCheckRule]]
            );

            $this->assertTrue($validator->passes());
        }
    }

    public function test_it_fails_validation_for_invalid_values()
    {
        $invalidValues = ['yes', 'no', '1', '0', 1, 0, 'random string', null];

        foreach ($invalidValues as $value) {
            $validator = Validator::make(
                ['input_key' => $value],
                ['input_key' => [new BooleanOrStringBooleanCheckRule]]
            );

            $this->assertFalse($validator->passes());
        }
    }
}

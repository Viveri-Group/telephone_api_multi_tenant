<?php

namespace Tests\Feature\API\ErrorHandling;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function test_validation_exception_handler()
    {
        $this->login();

        $this->post(route('competition.create'), [
            'end' => now()->addDay(),
            'max_paid_entries' => 5,
        ])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('message', 'The name field is required. (and 1 more error)')
                    ->where('data.0.source', 'name')
                    ->where('data.1.source', 'start')
                    ->etc();
            });
    }
}

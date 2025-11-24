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
                    ->has('message')
                    ->where('data.0.source', 'organisation_id')
                    ->where('data.1.source', 'name')
                    ->where('data.2.source', 'start')
                    ->etc();
            });
    }
}

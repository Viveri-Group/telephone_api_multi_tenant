<?php

namespace Tests\Feature\API\ErrorHandling;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NotFoundExceptionTest extends TestCase
{
    public function test_route_does_not_exist()
    {
        $this->json('GET', '/api/non-existent-route')
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('message', 'The route api/non-existent-route could not be found.')
                    ->etc();
            });
    }

    public function test_sanitized_message_is_working_as_expected()
    {
        $this->login();

        $this->get(route('competition.show', 5))
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('message', 'No query results for model Competition 5')
                    ->etc();
            });
    }
}

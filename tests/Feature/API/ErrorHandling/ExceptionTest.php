<?php

namespace Tests\Feature\API\ErrorHandling;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ExceptionTest extends TestCase
{
    public function test_not_authenticated()
    {
        $this->get(route('competition.show', 5))
            ->assertUnauthorized()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('message', 'Unauthenticated.')
                    ->etc();
            });
    }
}

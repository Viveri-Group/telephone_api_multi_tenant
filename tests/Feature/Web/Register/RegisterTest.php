<?php

namespace Tests\Feature\Web\Register;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_unable_to_reach_register_page()
    {
        $this->expectException(RouteNotFoundException::class);

        $this->get(route('register'));
    }
}

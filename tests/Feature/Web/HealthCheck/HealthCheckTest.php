<?php

namespace Tests\Feature\Web\HealthCheck;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_page()
    {
        $this->get(route('health-check'))
            ->assertOk();
    }
}

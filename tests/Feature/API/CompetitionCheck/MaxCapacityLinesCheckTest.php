<?php

namespace Tests\Feature\API\CompetitionCheck;

use App\Action\CapacityCheck\MaxLinesExceeded;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MaxCapacityLinesCheckTest extends TestCase
{
    public function test_enforce_number_of_lines_id_disabled_so_false_is_always_returned()
    {
        Config::set('system.ENFORCE_MAX_NUMBER_OF_LINES', false);
        Config::set('system.MAX_NUMBER_OF_LINES', 4);

        $this->assertFalse((new MaxLinesExceeded())->handle(100));
    }

    public function test_negative_1_returns_false()
    {
        Config::set('system.ENFORCE_MAX_NUMBER_OF_LINES', true);
        Config::set('system.MAX_NUMBER_OF_LINES', -1);

        $this->assertFalse((new MaxLinesExceeded())->handle(100));
    }

    public function test_4_is_greater_than_3()
    {
        Config::set('system.ENFORCE_MAX_NUMBER_OF_LINES', true);
        Config::set('system.MAX_NUMBER_OF_LINES', 4);

        $this->assertFalse((new MaxLinesExceeded())->handle(3));
    }

    public function test_4_is_equal_greater_than_4()
    {
        Config::set('system.ENFORCE_MAX_NUMBER_OF_LINES', true);
        Config::set('system.MAX_NUMBER_OF_LINES', 4);

        $this->assertTrue((new MaxLinesExceeded())->handle(4));
    }

    public function test_4_is_not_greater_than_5()
    {
        Config::set('system.ENFORCE_MAX_NUMBER_OF_LINES', true);
        Config::set('system.MAX_NUMBER_OF_LINES', 4);

        $this->assertTrue((new MaxLinesExceeded())->handle(5));
    }
}

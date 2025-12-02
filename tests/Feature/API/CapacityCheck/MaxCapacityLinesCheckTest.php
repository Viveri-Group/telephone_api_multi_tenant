<?php

namespace Tests\Feature\API\CapacityCheck;

use App\Action\CapacityCheck\MaxLinesExceeded;
use App\Models\Organisation;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MaxCapacityLinesCheckTest extends TestCase
{
    public function test_negative_1_returns_false()
    {
        $org = Organisation::factory(['max_number_of_lines' => -1])->create();

        $this->assertFalse((new MaxLinesExceeded())->handle($org->id, 100));
    }

    public function test_4_is_greater_than_3()
    {
        $org = Organisation::factory(['max_number_of_lines' => 4])->create();


        $this->assertFalse((new MaxLinesExceeded())->handle($org->id,3));
    }

    public function test_4_is_equal_greater_than_4()
    {
        $org = Organisation::factory(['max_number_of_lines' => 4])->create();

        $this->assertTrue((new MaxLinesExceeded())->handle($org->id,4));
    }

    public function test_4_is_not_greater_than_5()
    {
        $org = Organisation::factory(['max_number_of_lines' => 4])->create();

        $this->assertTrue((new MaxLinesExceeded())->handle($org->id,5));
    }
}

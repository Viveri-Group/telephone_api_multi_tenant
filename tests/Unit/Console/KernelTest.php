<?php

namespace Tests\Unit\Console;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use Tests\TestCase;

class KernelTest extends TestCase
{
    protected array $expectedCommands = [
        'model:prune',
        'viveri:active-call-clear-up',
        'viveri:process-phone-line-schedule'
    ];

    public function test_expected_commands_are_scheduled(): void
    {
        $actualCommands = [];

        collect(app()->make(Schedule::class)->events())->each(function (Event $event) use (&$actualCommands) {
            $actualCommands[] = Str::afterlast($event->command, "'artisan' ");
        });

        $this->assertEqualsCanonicalizing($this->expectedCommands, $actualCommands);
    }
}

<?php

namespace Tests\Unit\Job;

use App\Jobs\UpdateAPIRequestLogJob;
use App\Models\APIRequestLog;
use Tests\TestCase;

class UpdateAPIRequestLogJobTest extends TestCase
{
    public function test_works_as_expected()
    {
        $this->login();

        APIRequestLog::factory(['uuid'=>'foooo'])->create();

        (new UpdateAPIRequestLogJob(
            'foooo',
            'response_data',
            '200',
            123
        ))->handle();

        tap(APIRequestLog::first(), function (APIRequestLog $log) {
            $this->assertSame('response_data', $log->response_data);
            $this->assertSame('200', $log->response_status);
            $this->assertEquals('123', $log->duration);
        });
    }
}

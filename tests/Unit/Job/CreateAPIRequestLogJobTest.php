<?php

namespace Tests\Unit\Job;

use App\Jobs\CreateAPIRequestLogJob;
use App\Models\APIRequestLog;
use Tests\TestCase;

class CreateAPIRequestLogJobTest extends TestCase
{
    public function test_works_as_expected()
    {
        $user = $this->login();

        $this->assertCount(0, APIRequestLog::all());

        (new CreateAPIRequestLogJob(
            $user->id,
            'uuid_string',
            '10.10.10.10',
            'request_uri',
            ['foo' => 'bar'],
            ['baz' => 'bat'],
            now()
        ))->handle();

        $this->assertCount(1, $logs = APIRequestLog::all());

        tap($logs->first(), function (APIRequestLog $log) use ($user) {
            $this->assertSame('uuid_string', $log->uuid);
            $this->assertSame($user->id, $log->user_id);
            $this->assertSame('10.10.10.10', $log->ip_address);
            $this->assertSame('request_uri', $log->request_type);
            $this->assertSame(['foo' => 'bar'], $log->request_input);
            $this->assertSame(['baz' => 'bat'], $log->request_headers);
            $this->assertSame('', $log->response_data);
            $this->assertSame('', $log->response_status);
            $this->assertNotNull($log->created_at);
        });
    }
}

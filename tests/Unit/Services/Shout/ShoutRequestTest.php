<?php

namespace Tests\Unit\Services\Shout;

use App\Action\Services\MakeShoutRequestAction;
use App\Models\ShoutServerRequestLog;
use App\Services\Shout\Requests\BaseRequest;
use App\Services\Shout\Response\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\Helpers\Mocks\Shout;
use Tests\TestCase;

class ShoutRequestTest extends TestCase
{
    public function test_that_it_will_log_request()
    {
        Shout::fake();

        Config::set('system.SHOUT_SERVER.STAGING',
            [
                [
                    'identifier' => 'TEST SERVER ONE',
                    'ip_address' => '10.10.10.10',
                    'username' => 'username_1',
                    'password' => 'password_1'
                ]
            ]
        );

        Http::fake([
            'http://10.10.10.10/filemanager/api/global/foo' => Http::response('{"hello-world": "foo"}'),
        ]);

        $this->assertCount(0, ShoutServerRequestLog::all());

        $httpResponse = (new MakeShoutRequestAction())->handle(
            (new ExampleRequest())
        );

        $httpResponse = $httpResponse[0];

        $this->assertInstanceOf(Response::class, $httpResponse);

        $this->assertEquals([
            'hello-world' => 'foo',
        ], $httpResponse->toArray());

        $this->assertCount(1, $requests = ShoutServerRequestLog::all());

        [$request, $response] = Http::recorded()[0];

        $this->assertEquals('http://10.10.10.10/filemanager/api/global/foo', $request->url());
        $this->assertEquals('POST', $request->method());
        $this->assertEquals('{"foo":"bar"}', $request->body());

        tap($requests->first(), function (ShoutServerRequestLog $request) {
            $this->assertSame('TEST SERVER ONE', $request->identifier);
            $this->assertSame('200', $request->status_code);
            $this->assertSame('http://10.10.10.10/filemanager/api/global/foo', $request->url);
            $this->assertSame('POST', $request->http_method);
            $this->assertEquals(ExampleRequest::class, $request->request_type);
            $this->assertEquals(Response::class, $request->response_class);
            $this->assertEquals(['foo' => 'bar'], $request->request);
            $this->assertEquals(['hello-world' => 'foo'], $request->response);
            $this->assertNotNull($request->request_start);
            $this->assertNotNull($request->request_end);
            $this->assertIsInt($request->response_time);
            $this->assertEquals(1, $request->attempts);
        });
    }
}

class ExampleRequest extends BaseRequest
{
    protected function getUri(): string
    {
        return '/foo';
    }

    protected function getParams(): array
    {
        return [
            'foo' => 'bar',
        ];
    }

    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    protected function getToken(): string
    {
        return 'baz';
    }
}

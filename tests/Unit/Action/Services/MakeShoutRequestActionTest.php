<?php

namespace Tests\Unit\Action\Services;

use App\Action\Services\MakeShoutRequestAction;
use App\Exceptions\NoShoutServerSetupException;
use App\Models\FileUpload;
use App\Models\ShoutServerRequestLog;
use App\Services\Shout\Requests\Audio\AudioStore;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\Mocks\Shout;
use Tests\TestCase;

class MakeShoutRequestActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_request_can_be_made_as_expected()
    {
        Shout::fake();

        Storage::fake('local');

        UploadedFile::fake()->create('foo.wav', 1000, 'audio/wav');

        Storage::disk('local')->put(FileUpload::LOCAL_BASE . 'foo.wav', 'file contents');

        $fileUpload = FileUpload::factory([
            'filename' => 'foo.wav'
        ])->create();

        $params = [];

        (new MakeShoutRequestAction())->handle(
            (new AudioStore($fileUpload, $params))
        );

        $this->assertCount(1, $requestLogs = ShoutServerRequestLog::all());

        tap($requestLogs->get(0), function ($log) use ($fileUpload) {
            $this->assertSame('TEST SERVER ONE', $log->identifier);
            $this->assertSame('App\Services\Shout\Requests\Audio\AudioStore', $log->request_type);
            $this->assertSame(['params' => [], 'server' => [], 'fileUpload' => $fileUpload->id], $log->request_input);
            $this->assertSame('App\Services\Shout\Response\Response', $log->response_class);
            $this->assertSame([], $log->request);
        });
    }

    public function test_no_servers_are_specified()
    {
        Shout::fake();

        Storage::fake('local');

        Config::set('system.SHOUT_SERVER.STAGING', []);

        UploadedFile::fake()->create('foo.wav', 1000, 'audio/wav');

        Storage::disk('local')->put(FileUpload::LOCAL_BASE . 'foo.wav', 'file contents');

        $fileUpload = FileUpload::factory([
            'filename' => 'foo.wav'
        ])->create();

        $params = [];

        $this->expectException(NoShoutServerSetupException::class);

        (new MakeShoutRequestAction())->handle(
            (new AudioStore($fileUpload, $params))
        );
    }

    public function test_multiple_servers_are_supported_and_requests_can_be_made_as_expected()
    {
        Shout::fake();

        Config::set('system.SHOUT_SERVER.STAGING',
            [
                [
                    'identifier' => 'TEST SERVER ONE',
                    'ip_address' => '1.1.1.1',
                    'username' => 'username_1',
                    'password' => 'password_1'
                ],
                [
                    'identifier' => 'TEST SERVER TWO',
                    'ip_address' => '2.2.2.2',
                    'username' => 'username_2',
                    'password' => 'password_2'
                ]
            ]
        );

        Storage::fake('local');

        UploadedFile::fake()->create('foo.wav', 1000, 'audio/wav');

        Storage::disk('local')->put(FileUpload::LOCAL_BASE . 'foo.wav', 'file contents');

        $fileUpload = FileUpload::factory([
            'filename' => 'foo.wav'
        ])->create();

        $params = [];

        (new MakeShoutRequestAction())->handle(
            (new AudioStore($fileUpload, $params))
        );

        $this->assertCount(2, $requestLogs = ShoutServerRequestLog::all());

        tap($requestLogs->get(0), function ($log) use ($fileUpload) {
            $this->assertSame('TEST SERVER ONE', $log->identifier);
            $this->assertSame('App\Services\Shout\Requests\Audio\AudioStore', $log->request_type);
            $this->assertSame(['params' => [], 'server' => [], 'fileUpload' => $fileUpload->id], $log->request_input);
            $this->assertSame('App\Services\Shout\Response\Response', $log->response_class);
            $this->assertSame([], $log->request);
        });

        tap($requestLogs->get(1), function ($log) use ($fileUpload) {
            $this->assertSame('TEST SERVER TWO', $log->identifier);
            $this->assertSame('App\Services\Shout\Requests\Audio\AudioStore', $log->request_type);
            $this->assertSame(['params' => [], 'server' => [], 'fileUpload' => $fileUpload->id], $log->request_input);
            $this->assertSame('App\Services\Shout\Response\Response', $log->response_class);
            $this->assertSame([], $log->request);
        });
    }
}

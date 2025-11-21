<?php

namespace Tests\Helpers\Mocks;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Shout
{
    public static function fake(): void
    {
        Config::set('system.SHOUT_SERVER_ACTIVE_ENVIRONMENT', 'STAGING');

        Config::set('system.SHOUT_SERVER.STAGING',
            [
                [
                    'identifier' => 'TEST SERVER ONE',
                    'ip_address' => '1.1.1.1',
                    'username' => 'username_1',
                    'password' => 'password_1'
                ]
            ]
        );

        Http::fake([
            'http://1.1.1.1/filemanager/api/global' => Http::response(self::getResponse('file-index')),
            'http://2.2.2.2/filemanager/api/global' => Http::response(self::getResponse('file-index')),
            'http://1.1.1.1/filemanager/api/global/555' => Http::response(self::getResponse('file-delete')),
            'http://3.3.3.3/filemanager/api/global' => Http::response(self::getResponse('file-store')),
        ]);
    }

    protected static function getResponse(string $fileName): string
    {
        return File::get(base_path('tests/Helpers/Mocks/ShoutResponses/' . $fileName . '.json'));
    }
}

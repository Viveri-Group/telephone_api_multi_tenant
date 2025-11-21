<?php

namespace App\Action\Services;

use App\DTO\Shout\ShoutServerDTO;
use App\Exceptions\NoShoutServerSetupException;
use App\Services\Shout\Requests\BaseRequest;

class MakeShoutRequestAction
{
    /**
     * @throws NoShoutServerSetupException
     */
    public function handle(BaseRequest $request): array
    {
        $response = [];

        $environment = config('system.SHOUT_SERVER_ACTIVE_ENVIRONMENT');

        $shoutServers = config("system.SHOUT_SERVER.{$environment}");

        if(empty($shoutServers)){
            throw new NoShoutServerSetupException();
        }

        collect($shoutServers)->each(function ($serverData) use ($request, &$response) {
            $request->setServer(
                new ShoutServerDTO(
                    $serverData['identifier'],
                    $serverData['ip_address'],
                    $serverData['username'],
                    $serverData['password'],
                )
            );

            $response[] = $request->handle();
        });

        return $response;
    }
}

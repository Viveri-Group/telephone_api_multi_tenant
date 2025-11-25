<?php

return [
    'NOTIFICATIONS' => [
        'teams' => [
            'webhook_url' => env('DMB_TEAMS_WEBHOOK_URL')
        ]
    ],

    'DOWNLOAD' => [
        'ENTRIES_DOWNLOAD_RESULTS_PER_PAGE' => env('ENTRIES_DOWNLOAD_RESULTS_PER_PAGE', 50),
        'DEFAULT_HIDDEN_CLI_PRESENTATION' =>  env('DEFAULT_HIDDEN_CLI_PRESENTATION', '000000000000'),
    ],

    'ENFORCE_MAX_NUMBER_OF_LINES' => env('ENFORCE_MAX_NUMBER_OF_LINES', false),
    'MAX_NUMBER_OF_LINES' => env('MAX_NUMBER_OF_LINES', 2_000),

    'SHOUT_SERVER_ACTIVE_ENVIRONMENT' => env('SHOUT_SERVER_ACTIVE_ENVIRONMENT', 'STAGING'),

    'SHOUT_SERVER' => [
        'STAGING' => [
            [
                'identifier' => 'Staging Shout Server',
                'ip_address' => env('SHOUT_SERVER_1_SANDBOX_IP'),
                'username' => env('SHOUT_SERVER_1_SANDBOX_USERNAME'),
                'password' => env('SHOUT_SERVER_1_SANDBOX_PASSWORD')
            ]
        ],
        'PRODUCTION' => [
            [
                'identifier' => 'Production Shout Server',
                'ip_address' => env('SHOUT_SERVER_1_PRODUCTION_IP'),
                'username' => env('SHOUT_SERVER_1_PRODUCTION_USERNAME'),
                'password' => env('SHOUT_SERVER_1_PRODUCTION_PASSWORD'),
            ]
        ]
    ],

    'show_logo_to_guests' => env('SHOW_LOGO_TO_GUESTS', false),

    'LOG_API_REQUEST_USING_QUEUE' => env('LOG_API_REQUEST_USING_QUEUE', false),
];

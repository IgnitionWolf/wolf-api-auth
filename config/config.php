<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Namespace
    |--------------------------------------------------------------------------
    |
    | Default application namespace.
    |
    */

    'namespace' => 'App',

    /*
    |--------------------------------------------------------------------------
    | Stubs
    |--------------------------------------------------------------------------
    |
    | Stubs and their corresponding target directories.
    |
    */

    'stubs' => [
        'path' => base_path('vendor/ignitionwolf/wolf-api-auth/src/Commands/stubs'),
        'files' => [
            'events/authenticated' => 'Events/UserAuthenticated',
            'events/logged-in' => 'Events/UserLoggedIn',
            'events/registered' => 'Events/UserRegistered',
            'controllers/authentication' => 'Http/Controllers/AuthenticationController',
            'requests/login' => 'Http/Requests/Authentication/LoginRequest',
            'requests/register' => 'Http/Requests/Authentication/RegisterRequest',
            'requests/social' => 'Http/Requests/Authentication/SocialRequest',
        ],
    ],
];

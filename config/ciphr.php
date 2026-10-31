<?php

return [
    // The subdomain of your Ciphr portal: {portal}.app.ciphr.com
    'portal' => env('CIPHR_PORTAL'),
    'api_key' => env('CIPHR_API_KEY'),

    // Only needed when the API is not at https://{portal}.app.ciphr.com/api
    'base_url' => env('CIPHR_BASE_URL'),

    // Other portals, used with Ciphr::connection('name'):
    // 'connections' => [
    //     'other' => [
    //         'portal' => env('CIPHR_OTHER_PORTAL'),
    //         'api_key' => env('CIPHR_OTHER_API_KEY'),
    //     ],
    // ],
    'connections' => [],

    'timeouts' => [
        'connect' => env('CIPHR_CONNECT_TIMEOUT', 10),
        'request' => env('CIPHR_REQUEST_TIMEOUT', 30),
    ],

    'retries' => [
        'times' => env('CIPHR_RETRIES'),
        'interval_ms' => 500,
    ],

    'cache' => [
        'enabled' => env('CIPHR_CACHE', false),
        'store' => env('CIPHR_CACHE_STORE'),
        'seconds' => 3600,
    ],

    'pagination' => [
        'per_page' => 100,
    ],
];

<?php

return [
    'api_endpoint' => env('CURRENCY_API_ENDPOINT', ''),
    'database' => [
        'name' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ]
];
<?php

return [
    'paths' => [
        'api/*',
        'login',
        'logout',
        'register',
        'documentation',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'https://sebumi-production.netlify.app',
        'http://localhost:8000',
        'http://127.0.0.1:8000',
        // hapus '*' agar sesuai dengan credentials
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

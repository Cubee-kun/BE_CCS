<?php

return [
    'paths' => [
        'api/*',
        'login',
        'logout',
        'register',
        'documentation',
        'api/documentation', // l5-swagger UI
        'api-docs.json',     // file json swagger kustom di resources/views/swagger
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',   // tambahkan ini untuk FE Vite di 127.0.0.1
        'http://localhost:8000',
        'http://127.0.0.1:8000',
        'https://sebumi-production.netlify.app',
        'https://sebumi-ccs.netlify.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

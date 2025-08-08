<?php

return [
    'api' => [
        'title' => 'CCS API Documentation',
    ],
    'routes' => [
        'api' => 'api/documentation',
        'docs' => 'docs',
        'oauth2_callback' => 'api/oauth2-callback',
        'middleware' => [
            'api' => ['jwt.auth'],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],
    'paths' => [
        'docs' => storage_path('api-docs'),
        'annotations' => base_path('app'),
    ],
    'security' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ],
    ],
];

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
            'api' => ['auth:api'],
            'assets' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
        'group_options' => [],
    ],

    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => 'api-docs.json',
        'docs_yaml' => 'api-docs.yaml',
        'annotations' => [
            base_path('app'),
        ],
        'views' => base_path('resources/views/vendor/l5-swagger'),
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
        'excludes' => [],
    ],

    'security' => [
        'bearer_auth' => [
            'type' => 'apiKey',
            'name' => 'Authorization',
            'in' => 'header',
        ],
    ],

    'swagger_ui' => [
        'display' => [
            'filter' => true,
        ],
    ],

    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
    ],
];

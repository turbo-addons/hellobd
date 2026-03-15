<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://hellobd.news',
        'https://cdn.hellobd.news',
        'https://hellobd.ibos.io',
        'https://dev.hellobd.news',
        'http://localhost:3000',
        // 'http://localhost:8000',
        // 'http://127.0.0.1:8000/',
        'https://hello-bd.netlify.app',
        'https://front-hellobd.ibos.io',
        'https://test-hellobd.ibos.io'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];

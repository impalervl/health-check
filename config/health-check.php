<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Health Check List available checks
    |--------------------------------------------------------------------------
    |
    */
    'allowed_checks' => [
        App\Services\HealthCheckService\Checkers\CacheCheck::class,
        App\Services\HealthCheckService\Checkers\DataBaseCheck::class
    ],
];

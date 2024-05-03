<?php

use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::get('/health-check', HealthCheckController::class)
        ->name('health-check')
        ->middleware(['throttle:health-check']);
});

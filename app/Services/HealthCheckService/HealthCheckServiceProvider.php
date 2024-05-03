<?php

namespace App\Services\HealthCheckService;

use App\Services\HealthCheckService\Contracts\HealthCheckService as HealthCheckServiceInterface;
use App\Services\HealthCheckService\Events\HealthCheckPerformed;
use App\Services\HealthCheckService\Listeners\StoreHealthCheckResultListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Services\HealthCheckService\Contracts\ResultsCollection as ResultsCollectionContract;

class HealthCheckServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(HealthCheckServiceInterface::class, function (): HealthCheckServiceInterface {
            return $this->app->make(\App\Services\HealthCheckService\HealthCheckService::class);
        });

        $this->app->bind(ResultsCollectionContract::class, function (): ResultsCollectionContract {
            return $this->app->make(ResultsCollection::class);
        });
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Event::listen(
            HealthCheckPerformed::class,
            StoreHealthCheckResultListener::class,
        );
    }
}

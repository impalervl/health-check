<?php

namespace App\Services\HealthCheckService;

use App\Services\HealthCheckService\Contracts\HealthCheckService as HealthCheckServiceContract;
use App\Services\HealthCheckService\Contracts\ResultsCollection as ResultsCollectionContract;
use App\Services\HealthCheckService\Events\HealthCheckPerformed;

class HealthCheckService implements HealthCheckServiceContract
{

    /**
     * @var string
     */
    private string $context = 'default';

    /**
     * @return void
     * @throws \Exception
     */
    public function checkResources(): ResultsCollectionContract
    {
        $collection = app(ResultsCollectionContract::class);

        foreach (config('health-check.allowed_checks') as $check) {
            $result = app($check)->run();
            $collection->addResult($result);
        }

        event(new HealthCheckPerformed($collection, $this->context));
        return $collection;
    }

    /**
     * @param string $context
     * @return void
     */
    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}

<?php
namespace App\Services\HealthCheckService\Contracts;

use App\Services\HealthCheckService\Contracts\ResultsCollection as ResultsCollectionContract;

interface HealthCheckService
{
    /**
     * Check defined resources health.
     * @return void
     */
    public function checkResources(): ResultsCollectionContract;
}

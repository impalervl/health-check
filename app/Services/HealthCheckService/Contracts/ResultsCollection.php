<?php
namespace App\Services\HealthCheckService\Contracts;

use App\Services\HealthCheckService\Base\Result;
use Illuminate\Support\Collection;

interface ResultsCollection
{
    /**
     * Add atomic result to the aggregated collection.
     * @return void
     */
    public function addResult(Result $result): void;

    /**
     * Get summary result for the performed checks
     * @return void
     */
    public function allResults(): Collection;

    /**
     * Is app services are healthy
     * @return void
     */
    public function isHealthy(): bool;
}

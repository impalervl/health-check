<?php

namespace App\Services\HealthCheckService\Events;

use App\Services\HealthCheckService\ResultsCollection;

class HealthCheckPerformed
{
    public function __construct(public ResultsCollection $resultsCollection, public string $context){}
}

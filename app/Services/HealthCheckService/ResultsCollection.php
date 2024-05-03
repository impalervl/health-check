<?php

namespace App\Services\HealthCheckService;

use App\Services\HealthCheckService\Base\Result;
use App\Services\HealthCheckService\Contracts\ResultsCollection as ResultsCollectionContract;
use Illuminate\Support\Collection;

class ResultsCollection implements ResultsCollectionContract
{
    /**
     * Result statuses
     */
    public const SUCCESS = 'success';
    /**
     *
     */
    public const FAILED = 'failed';

    /**
     * @var array
     */
    private array $results = [];

    /**
     * @var string
     */
    private string $status = self::SUCCESS;

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->status == self::SUCCESS;
    }

    /**
     * @param Result $result
     * @return void
     */
    public function addResult(Result $result): void
    {
        $this->setStatus($result);
        $this->results[] = $result;
    }

    /**
     * @return Collection
     */
    public function allResults(): Collection
    {
        $collection = collect();

        foreach ($this->results as $result) {
            $collection->put($result->getName(), $result->isHealthy());
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function toDbStructure(): array
    {
        return [
            'is_success' => $this->isHealthy(),
            'results'    => json_encode($this->detailedResult())
        ];
    }

    /**
     * @return array
     */
    public function detailedResult(): array
    {
        $data = [];

        foreach ($this->results as $result) {
            $data[] = [
                'name'       => $result->getName(),
                'is_healthy' => $result->isHealthy(),
                'details'    => $result->getErrorMessage(),
            ];
        }

        return $data;
    }

    /**
     * @param Result $result
     * @return void
     */
    private function setStatus(Result $result): void
    {
        if (!$this->isHealthy()) {
            return;
        }

        $this->status = $result->isHealthy() ? self::SUCCESS : self::FAILED;
    }
}

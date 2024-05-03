<?php

namespace App\Services\HealthCheckService\Checkers;

use App\Services\HealthCheckService\Base\Check;
use App\Services\HealthCheckService\Base\Result;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheCheck extends Check
{
    /**
     * @var string|null
     */
    protected ?string $name = 'cache';

    /**
     * @var string
     */
    protected string $driver = 'cache.default';

    /**
     * @return Result
     */
    public function run(): Result
    {
        $result = new Result($this);
        try {
            $this->canWriteValuesToCache($this->getConnection())
                ? $result->ok()
                : $result->failed('Could not set or retrieve an application cache value.');
        } catch (\Throwable $exception) {
            $result->failed("An exception occurred with the application cache: `{$exception->getMessage()}`");
        }

        return $result;
    }

    /**
     * @return Repository
     */
    protected function getConnection(): Repository
    {
        return Cache::driver(config($this->driver));
    }

    /**
     * @param Repository $store
     *
     * @return bool
     */
    protected function canWriteValuesToCache(Repository $store): bool
    {
        $expectedValue = Str::random(15);
        $cacheKey = 'cache_check';

        $store->put($cacheKey, $expectedValue ,30);

        $actualValue = $store->get($cacheKey);

        return $actualValue === $expectedValue;
    }
}

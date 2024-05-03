<?php

namespace App\Services\HealthCheckService\Checkers;

use App\Services\HealthCheckService\Base\Check;
use App\Services\HealthCheckService\Base\Result;
use Exception;
use Illuminate\Support\Facades\DB;

class DataBaseCheck extends Check
{
    /**
     * @var string|null
     */
    protected ?string $name = 'db';

    /**
     * @return Result
     */
    public function run(): Result
    {
        $result = new Result($this);

        try {
            DB::connection($this->getConnection())->getPdo();
            $result->ok();
        } catch (Exception $exception) {
            $result->failed("Could not connect to the database: `{$exception->getMessage()}`");
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getConnection(): string
    {
        return config('database.default');
    }
}

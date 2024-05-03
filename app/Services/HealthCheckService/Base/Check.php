<?php

namespace App\Services\HealthCheckService\Base;

use Illuminate\Support\Str;

abstract class Check
{
    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @return Result
     */
    abstract public function run(): Result;

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $baseName = class_basename(static::class);

        return Str::of($baseName)->beforeLast('Check');
    }
}

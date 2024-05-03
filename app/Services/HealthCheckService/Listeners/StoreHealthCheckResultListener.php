<?php

namespace App\Services\HealthCheckService\Listeners;

use App\Services\HealthCheckService\Events\HealthCheckPerformed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreHealthCheckResultListener
{
    /**
     * @param HealthCheckPerformed $event
     * @return void
     */
    public function handle(HealthCheckPerformed $event): void
    {
        $data = array_merge(
            $event->resultsCollection->toDbStructure(),
            ['owner' => $event->context],
            $this->timestamps(),
        );

        DB::table('health_check_results')->insert($data);
    }

    /**
     * @return array
     */
    private function timestamps(): array
    {
        $now = Carbon::now();
        return [
            'created_at' => $now,
            'updated_at' => $now
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthCheckRequest;
use App\Services\HealthCheckService\Contracts\HealthCheckService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class HealthCheckController extends Controller
{
    /**
     * @param HealthCheckService $healthCheckService
     * @return JsonResponse
     */
    public function __invoke(HealthCheckService $healthCheckService, HealthCheckRequest $request): JsonResponse
    {
        $healthCheckService->setContext($request->header($request::OWNER_HEADER));
        $results = $healthCheckService->checkResources();
        $code = $results->isHealthy() ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response()->json($results->allResults())->setStatusCode($code);
    }
}

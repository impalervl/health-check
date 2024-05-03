<?php

namespace Tests\Feature;

use App\Services\HealthCheckService\Checkers\CacheCheck;
use App\Services\HealthCheckService\Checkers\DataBaseCheck;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HealthCheckControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Validate.
     * @param $uuid
     * @param $expectedStatus
     * @return void
     */
    #[DataProvider('healthCheckEndpointHeaderDataProvider')]
    public function testHealthCheckWithOwnerHeader($uuid, $expectedStatus): void
    {
        $response = $this->withHeaders(['X-Owner' => $uuid])
            ->get($this->getUrl());

        $response->assertStatus($expectedStatus);
    }

    /**
     * @return void
     */
    public function testHealthCheckWithoutHeader(): void
    {
        $response = $this->get($this->getUrl());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testHealthCheckThrottleResponse(): void
    {
        for ($i = 0; $i < 61; $i++) {
            $response = $this->withHeaders(['X-Owner' => '84f435c3-1f61-4197-93cb-dde8a0fa1baf'])
                ->get($this->getUrl());
        }
        Artisan::call('cache:clear');
        $response->assertStatus(429);
    }

    /**
     * Validate.
     * @param array $data
     * @param $status
     * @return void
     */
    #[DataProvider('healthCheckServiceResultsDataProvider')]
    public function testHealthCheckServiceResults(array $data, $status): void
    {
       $this->mockChecks($data);
       $faker= Factory::create();
       $uuid = $faker->uuid;
       $success = $status === Response::HTTP_OK;

        $response = $this->withHeaders(['X-Owner' => $uuid])
            ->get($this->getUrl());

        $recordedData = DB::table('health_check_results')->where('owner', $uuid)->first();

        foreach (json_decode($recordedData->results, true) as $result) {
            $this->assertEquals($result['is_healthy'], $data[$result['name']]);
        }

        $this->assertEquals($success, $recordedData->is_success);
        $response->assertJson($data);
        $response->assertStatus($status);
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return route('api.v1.health-check');
    }

    /**
     * @param array $data
     * @return void
     */
    private function mockChecks(array $data): void
    {
        if (!Arr::get($data, 'cache')) {
            $this->partialMock(CacheCheck::class, function (MockInterface $mock) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('canWriteValuesToCache')->andReturn(false);
            });
        }

        if (!Arr::get($data, 'db')) {
            $this->partialMock(DataBaseCheck::class, function (MockInterface $mock) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getConnection')->andReturn('adasdasdad');
            });
        }
    }

    /**
     * @return \Generator
     */
    public static function healthCheckEndpointHeaderDataProvider(): \Generator
    {
        yield ['84f435c3-1f61-4197-93cb-dde8a0fa1baf', Response::HTTP_OK];
        yield ['invalid-uuid', Response::HTTP_UNPROCESSABLE_ENTITY];
        yield [null, Response::HTTP_UNPROCESSABLE_ENTITY];
    }

    /**
     * @return \Generator
     */
    public static function healthCheckServiceResultsDataProvider(): \Generator
    {
        yield [
            [
                'cache' => false,
                'db'    => true
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        ];
        yield [
            [
                'cache' => true,
                'db'    => false
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        ];
        yield [
            [
                'cache' => true,
                'db'    => true
            ],
            Response::HTTP_OK
        ];
        yield [
            [
                'cache' => false,
                'db'    => false
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }
}

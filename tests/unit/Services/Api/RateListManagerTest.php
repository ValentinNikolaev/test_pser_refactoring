<?php

declare(strict_types=1);

namespace unit\Services\Api;

use App\Client\ExchangeRatesClient;
use App\Exceptions\ApiRatesException;
use App\Service\Api\RateListManager;
use DomainException;
use Psr\Log\NullLogger;
use UnitTester;

class RateListManagerTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function testClientError(): void
    {
        $errorType = 'some';
        $errorInfo = 'some_info';
        $errorCode = 13241324;

        $client = $this->make(
            ExchangeRatesClient::class,
            [
                'getAll' => [
                    'success' => false,
                    'error' => [
                        'type' => $errorType,
                        'info' => $errorInfo,
                        'code' => $errorCode,
                    ],
                ],
            ]
        );
        $service = new RateListManager($client, new NullLogger());


        $this->tester->expectThrowable(new ApiRatesException("$errorType: $errorInfo", $errorCode),
            function () use ($service) {
                $service->getLatest();
            });
    }

    public function testMissingRates(): void
    {
        $client = $this->make(
            ExchangeRatesClient::class,
            [
                'getAll' => [
                    'success' => 'true',
                ],
            ]
        );
        $service = new RateListManager($client, new NullLogger());
        $this->tester->expectThrowable(new DomainException("Missing rates section in response"),
            function () use ($service) {
                $service->getLatest();
            });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testSuccess(): void
    {
        $rate = (float) random_int(1, 2000000);
        $client = $this->make(
            ExchangeRatesClient::class,
            [
                'getAll' => [
                    'success' => 'true',
                    'rates' => [
                        'EUR' => $rate,
                    ],
                ],
            ]
        );

        $service = new RateListManager($client, new NullLogger());
        $result = $service->getLatest();

        $this->tester->assertSame(
            [
                'dtos' => [
                    ['rate' => $rate, 'currency_code' => 'EUR'],
                ],
            ],
            $result->jsonSerialize());
    }
}

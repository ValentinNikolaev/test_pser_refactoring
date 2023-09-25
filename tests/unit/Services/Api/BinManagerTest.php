<?php

declare(strict_types=1);

namespace unit\Services\Api;

use App\Client\BinClient;
use App\Service\Api\BinManager;
use DomainException;
use stdClass;
use UnitTester;

class BinManagerTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    /**
     * @throws \Exception
     */
    public function testMissingAlpha(): void
    {
        $clientResponse = new stdClass();
        $clientResponse->country = new stdClass();

        $client = $this->make(
            BinClient::class,
            [
                'getById' => $clientResponse,
            ]
        );

        $service = new BinManager($client);

        $this->tester->expectThrowable(new DomainException("Country 'alpha2' property is missing"),
            function () use ($service) {
                $service->getCountryAlpha(random_int(1, 2000000));
            });
    }

    /**
     * @throws \Exception
     */
    public function testMissingCountry(): void
    {
        $clientResponse = new stdClass();

        $client = $this->make(
            BinClient::class,
            [
                'getById' => $clientResponse,
            ]
        );

        $service = new BinManager($client);

        $this->tester->expectThrowable(new DomainException("Country property is missing"), function () use ($service) {
            $service->getCountryAlpha(random_int(1, 2000000));
        });
    }

    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $alpha2 = "EUR";
        $clientResponse = new stdClass();
        $clientResponse->country = new stdClass();
        $clientResponse->country->alpha2 = $alpha2;

        $client = $this->make(
            BinClient::class,
            [
                'getById' => $clientResponse,
            ]
        );

        $service = new BinManager($client);
        $result = $service->getCountryAlpha(random_int(1, 2000000));

        $this->tester->assertSame($alpha2, $result);
    }
}

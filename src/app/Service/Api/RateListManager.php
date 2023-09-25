<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Client\SimpleRatesClient;
use App\DTO\RateDTO;
use App\DTO\RatesCollectionDTO;
use App\Exceptions\ApiRatesException;
use DomainException;
use Psr\Log\LoggerInterface;

class RateListManager
{
    private SimpleRatesClient $client;
    private LoggerInterface $logger;

    public function __construct(SimpleRatesClient $client, LoggerInterface $logger)
    {
        $this->client = $client;
    }

    /**
     * @throws \Throwable
     */
    public function getLatest(): RatesCollectionDTO
    {
        $rates = [];
        $data = $this->client->getAll();

        if (isset($data['success']) && !$data['success']) {
            throw new ApiRatesException(
                $data['error']['type'].": ".$data['error']['info'], $data['error']['code']
            );
        }

        if (!isset($data['rates'])) {
            throw new DomainException("Missing rates section in response");
        }

        foreach ($data['rates'] as $currencyCode => $rate) {
            $rates[] = new RateDTO(
                (string) $currencyCode,
                (float) $rate
            );
        }

        return new RatesCollectionDTO(...$rates);
    }
}
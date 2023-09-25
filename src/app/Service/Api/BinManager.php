<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Client\SimpleBinClient;
use DomainException;

class BinManager
{
    private SimpleBinClient $client;

    public function __construct(SimpleBinClient $client)
    {
        $this->client = $client;
    }

    public function getCountryAlpha(int $id): string
    {
        $data = $this->client->getById($id);
        if (!property_exists($data, 'country')) {
            throw new DomainException("Country property is missing");
        }

        if (!property_exists($data->country, 'alpha2')) {
            throw new DomainException("Country 'alpha2' property is missing");
        }

        return $data->country->alpha2;
    }
}
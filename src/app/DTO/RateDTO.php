<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class RateDTO implements JsonSerializable
{
    private string $currencyCode;
    private float $rate;

    public function __construct(string $currencyCode, float $rate)
    {
        $this->currencyCode = $currencyCode;
        $this->rate = $rate;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function jsonSerialize(): array
    {
        return [
            'rate' => $this->getRate(),
            'currency_code' => $this->getCurrencyCode(),
        ];
    }
}
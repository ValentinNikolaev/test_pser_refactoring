<?php

declare(strict_types=1);

namespace App\DTO;

class InputDTO
{
    private int $bin;
    private float $amount;
    private string $currencyCode;

    public function __construct(int $bin, float $amount, string $currencyCode)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
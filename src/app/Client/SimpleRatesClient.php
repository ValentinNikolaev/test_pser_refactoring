<?php

declare(strict_types=1);

namespace App\Client;

interface SimpleRatesClient
{
    public function getAll(): array;
}
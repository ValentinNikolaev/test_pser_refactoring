<?php

declare(strict_types=1);

namespace App\Service;

interface SimpleCountryCheckerContract
{
    public function check(string $countryAlpha2): bool;
}
<?php

declare(strict_types=1);

namespace App\Service\Calculation;

interface SimpleCountryFactorBuilderContract
{
    public function build(string $countryAlpha2): float;
}
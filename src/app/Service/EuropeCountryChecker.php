<?php

declare(strict_types=1);

namespace App\Service;

class EuropeCountryChecker implements SimpleCountryCheckerContract
{
    private array $euAlpha2List;

    public function __construct(array $euAlpha2List = [])
    {
        $this->euAlpha2List = $euAlpha2List;
    }

    public function check(string $countryAlpha2): bool
    {
        return in_array($countryAlpha2, $this->euAlpha2List, true);
    }
}
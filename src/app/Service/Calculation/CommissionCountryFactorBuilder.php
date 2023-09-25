<?php

declare(strict_types=1);

namespace App\Service\Calculation;

use App\Service\SimpleCountryCheckerContract;

class CommissionCountryFactorBuilder implements SimpleCountryFactorBuilderContract
{
    private float $rateEu = 0.01;
    private float $rateNonEu = 0.02;
    private SimpleCountryCheckerContract $checker;

    public function __construct(SimpleCountryCheckerContract $checker, array $params = [])
    {
        if (isset($params['rate_eu']) && (float) $params['rate_eu'] > 0) {
            $this->rateEu = (float) $params['rate_eu'];
        }
        if (isset($params['rate_non_eu']) && (float) $params['rate_non_eu'] > 0) {
            $this->rateNonEu = (float) $params['rate_non_eu'];
        }
        $this->checker = $checker;
    }

    public function build(string $countryAlpha2): float
    {
        $isEu = $this->checker->check($countryAlpha2);
        return $isEu ? $this->rateEu : $this->rateNonEu;
    }
}
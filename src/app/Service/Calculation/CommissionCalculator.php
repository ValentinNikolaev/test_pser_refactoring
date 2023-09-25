<?php

declare(strict_types=1);

namespace App\Service\Calculation;

use App\DTO\InputDTO;
use App\DTO\RateDTO;

class CommissionCalculator
{
    private SimpleCountryFactorBuilderContract $factorBuilder;
    private int $precision = 2;

    public function __construct(SimpleCountryFactorBuilderContract $factorBuilder, array $params = [])
    {
        if (isset($params['precision']) && (int) $params['precision'] > 0) {
            $this->precision = (int) $params['precision'];
        }

        $this->factorBuilder = $factorBuilder;
    }

    public function calculate(RateDTO $rate, InputDTO $inputDTO, string $countryAlpha2): float
    {
        $fixedAmount = 0;
        $baseAmount = $inputDTO->getAmount();
        $baseCurrency = $inputDTO->getCurrencyCode();

        if ($baseCurrency === 'EUR' || !$rate->getRate()) {
            $fixedAmount = $baseAmount;
        }
        if ($baseCurrency !== 'EUR' || $rate->getRate() > 0) {
            $fixedAmount = $baseAmount / $rate->getRate();
        }

        return $this->ceilWithPrecision($fixedAmount * $this->factorBuilder->build($countryAlpha2));
    }

    private function ceilWithPrecision($number)
    {
        $multiplier = 10 ** $this->precision;
        return ceil($number * $multiplier) / $multiplier;
    }
}
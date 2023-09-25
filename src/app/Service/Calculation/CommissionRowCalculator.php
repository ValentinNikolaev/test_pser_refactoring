<?php

declare(strict_types=1);

namespace App\Service\Calculation;

use App\DTO\RatesCollectionDTO;
use App\Exceptions\BinException;
use App\Exceptions\InputDtoException;
use App\Exceptions\RatesCollectionException;
use App\Service\Api\BinManager;
use App\Service\InputDtoBuilder;
use Throwable;

class CommissionRowCalculator
{
    private InputDtoBuilder $dtoBuilder;
    private BinManager $binManager;
    private CommissionCalculator $calculator;
    private RatesCollectionDTO $ratesCollection;

    public function __construct(
        CommissionCalculator $calculator,
        InputDtoBuilder $dtoBuilder,
        BinManager $binManager,
        RatesCollectionDTO $ratesCollection
    ) {
        $this->dtoBuilder = $dtoBuilder;
        $this->binManager = $binManager;
        $this->calculator = $calculator;
        $this->ratesCollection = $ratesCollection;
    }

    /**
     * @throws \App\Exceptions\InputDtoException
     * @throws \App\Exceptions\BinException
     * @throws \App\Exceptions\RatesCollectionException
     */
    public function calculate(string $stringRow): float
    {
        try {
            $inputDto = $this->dtoBuilder->buildFromJson($stringRow);
        } catch (Throwable $throwable) {
            throw new InputDtoException($throwable->getMessage(), 0, $throwable);
        }

        $rate = $this->ratesCollection->getDtoByCurrency(
            $inputDto->getCurrencyCode()
        );

        if (!$rate) {
            throw new RatesCollectionException("Missing currency '{$inputDto->getCurrencyCode()}' in rates collection");
        }

        try {
            $countryAlpha2 = $this->binManager->getCountryAlpha(
                $inputDto->getBin()
            );
        } catch (Throwable $throwable) {
            throw new BinException($throwable->getMessage(), 0, $throwable);
        }

        return $this->calculator->calculate(
            $rate,
            $inputDto,
            $countryAlpha2
        );
    }
}
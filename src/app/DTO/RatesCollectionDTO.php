<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class RatesCollectionDTO implements JsonSerializable
{
    private array $dtos;

    public function __construct(RateDTO ...$dtos)
    {
        $this->dtos = $dtos;
    }

    public function getDtoByCurrency(string $currency): ?RateDTO
    {
        $filteredDtos = array_filter(
            $this->dtos,
            static function (RateDTO $DTO) use ($currency) {
                return $DTO->getCurrencyCode() === $currency;
            }
        );

        return $filteredDtos ? $filteredDtos[0] : null;
    }

    /**
     * @return array<RateDTO>
     */
    public function getDtos(): array
    {
        return $this->dtos;
    }

    public function jsonSerialize(): array
    {
        return [
            'dtos' => array_map(static fn(RateDTO $DTO) => $DTO->jsonSerialize(), $this->dtos),
        ];
    }
}
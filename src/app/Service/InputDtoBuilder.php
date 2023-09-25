<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\InputDTO;
use InvalidArgumentException;

class InputDtoBuilder
{
    /**
     * @throws \JsonException
     */
    public function buildFromJson(string $json): InputDTO
    {
        $inputArray = json_decode(
            $json,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!isset($inputArray["bin"])) {
            throw new InvalidArgumentException("Missing 'bin' key");
        }

        if (!isset($inputArray["amount"])) {
            throw new InvalidArgumentException("Missing 'amount' key");
        }

        if (!isset($inputArray["currency"])) {
            throw new InvalidArgumentException("Missing 'currency' key");
        }

        return new InputDTO(
            (int) $inputArray["bin"],
            (float) $inputArray["amount"],
            strtoupper((string) $inputArray["currency"])
        );
    }
}
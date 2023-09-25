<?php

declare(strict_types=1);

use App\Exceptions\BinException;
use App\Service\Calculation\CommissionRowCalculator;

if (!function_exists('printErr')) {
    function printErr(string $message, int $line = -1)
    {
        echo sprintf("Error on line '%d' with error: %s", $line, $message).PHP_EOL;
    }
}

if (!function_exists('isCli')) {
    function isCli(): bool
    {
        if (defined('STDIN')) {
            return true;
        }

        if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        }

        return false;
    }
}


if (!function_exists('checkCli')) {
    function checkCli()
    {
        if (!isCli()) {
            throw new InvalidArgumentException("Only CLI is supported");
        }
    }
}

if (!function_exists('processInputFile')) {
    function processInputFile(SplFileObject $file, CommissionRowCalculator $rowCalculator): Generator
    {
        $line = 0;
        while (!$file->eof()) {
            $line++;
            $stringRow = $file->fgets();
            if (empty($stringRow)) {
                break;
            }

            try {
                $commission = $rowCalculator->calculate($stringRow);
            } catch (BinException $throwable) {
                printErr("CRITICAL: ".$throwable->getMessage(), $line);
                exit(1);
            } catch (Throwable $throwable) {
                printErr("WARNING: ".$throwable->getMessage(), $line);
                continue;
            }

            yield $commission;
        }
    }
}
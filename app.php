<?php

use App\Client as ApiClient;
use App\Service\Api as ApiService;
use App\Service\Calculation;
use App\Service\EuropeCountryChecker;
use App\Service\InputDtoBuilder;
use Psr\Log\NullLogger;

/**
 * Decide not to do separate framework like solution because we really dont need. Try to use only native PHP and PSR
 * Also make super simple functions list because moving everything to classes is really time consume
 */

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/src/autoload/functions.php';

try {
    checkCli();

    $argc = count($argv);
    if (count($argv) < 2) {
        throw new \InvalidArgumentException('Missing first argument with filename/file dir');
    }

    $filename = $argv[1];
    if (!file_exists($filename)) {
        throw new \InvalidArgumentException("File '$filename' doesnt exists");
    }

    /**
     * App Initialization
     */
    $logger = new NullLogger();
    $ratesCollection = (new ApiService\RateListManager(
        new ApiClient\ExchangeRatesClient(),
        $logger)
    )->getLatest();

    $binManager = new ApiService\BinManager(
        new ApiClient\BinClient(),
    );

    $dtoBuilder = new InputDtoBuilder();

    $euCountriesChecker = new EuropeCountryChecker(
        include 'src/config/europe_alpha2.php' ?? []
    );

    $factorBuilder = new Calculation\CommissionCountryFactorBuilder(
        $euCountriesChecker
    );

    $calculator = new Calculation\CommissionCalculator(
        $factorBuilder
    );

    $rowCalculator = new Calculation\CommissionRowCalculator(
        $calculator,
        $dtoBuilder,
        $binManager,
        $ratesCollection
    );

    /**
     * Program running
     */
    $file = new SplFileObject($filename, 'r');
    foreach (processInputFile($file, $rowCalculator) as $commission) {
        echo $commission.PHP_EOL;
    }
} catch (Throwable $throwable) {
    echo "EXCEPTION: ".$throwable->getMessage().PHP_EOL;
    exit(1);
}

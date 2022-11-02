<?php
require_once 'vendor/autoload.php';

use App\Contract\Commission\CalculatorInterface;
use App\Exception\BIN\ResolverException;
use App\Exception\Transaction\TransformationException;
use App\Service\Transformer\StringToTransactionTransformer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;

$env = new Dotenv();
$env->loadEnv(__DIR__ . '/.env');

$containerBuilder = new ContainerBuilder();

$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('config/services.yaml');

$containerBuilder->setParameter('exchange_rates_api_key', $_ENV['EXCHANGE_RATES_API_KEY']);

bcscale($_ENV['BC_SCALE']);

/** @var StringToTransactionTransformer $transformer */
$transformer = $containerBuilder->get(StringToTransactionTransformer::class);

try {
    $file = new SplFileObject($argv[1]);
} catch (RuntimeException $e) {
    print $e->getMessage() . PHP_EOL;
    exit;
}

try {
    foreach ($file as $lineNumber => $row) {
        $row = trim($row);

        if (empty($row)) {
            continue;
        }

        try {
            /** @var CalculatorInterface $calculator */
            $calculator = $containerBuilder->get(CalculatorInterface::class);
            $message = $calculator->calculate($transformer->transform($row));
        } catch (TransformationException | ResolverException $e) {
            $message = "#$lineNumber: " . $e->getMessage();
        }

        print $message . PHP_EOL;
    }
} catch (Exception $e) {
    print "#$lineNumber: " . $e->getMessage() . PHP_EOL;
}

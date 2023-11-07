<?php

declare(strict_types = 1);

use Slim\App;
use Dotenv\Dotenv;
use Tracy\Debugger;
use DI\ContainerBuilder;
use App\Support\Settings\Settings;

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable([__DIR__ . '/../'], ['app.env'])->load();

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/settings.php')
    ->addDefinitions(__DIR__ . '/dependencies.php')
    ->build();

$settings = $container->get(Settings::class);

if($settings->get('app_stream') === 'dev') {
    Debugger::enable();
}

return $container;
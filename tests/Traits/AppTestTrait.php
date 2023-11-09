<?php

namespace App\Test\Traits;

use DI\ContainerBuilder;
use Slim\App;
use Selective\TestTrait\Traits\HttpTestTrait;
use Selective\TestTrait\Traits\ArrayTestTrait;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\ContainerTestTrait;

trait AppTestTrait
{
    use ArrayTestTrait;
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;

    protected App $app;

    protected function setUp(): void
    {
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        $c = (new ContainerBuilder())
        ->addDefinitions(__DIR__ . '/../../config/settings.php')
        ->addDefinitions(__DIR__ . '/../../config/dependencies.php')

        ->build();

        $this->app = $c->get(App::class);
        $this->setUpContainer($c);
    }

    
}
<?php

declare(strict_types = 1);

use Slim\App;

$container = require_once __DIR__ . '/../config/bootstrap.php';

($container->get(App::class))->run();
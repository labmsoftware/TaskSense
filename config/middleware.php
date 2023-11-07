<?php

declare(strict_types = 1);

use Slim\App;
use Selective\BasePath\BasePathMiddleware;

return function(App $app)
{
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
};
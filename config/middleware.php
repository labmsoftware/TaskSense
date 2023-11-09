<?php

declare(strict_types = 1);

use Slim\App;
use Selective\BasePath\BasePathMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;

return function(App $app)
{
    $app->addBodyParsingMiddleware(); 
    $app->addRoutingMiddleware(); 
    $app->add(SessionStartMiddleware::class); 
    $app->add(BasePathMiddleware::class);
};
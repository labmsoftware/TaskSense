<?php

declare(strict_types = 1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Http\Action\Auth\DoLoginAction;
use App\Http\Action\Auth\ViewLoginAction;
use App\Http\Action\Auth\DoSignpostAction;

return function(App $app) 
{
    $app->get('', DoSignpostAction::class);

    $app->group('/auth', function(RouteCollectorProxy $auth) {
        $auth->get('/login', ViewLoginAction::class);
        $auth->get('/logout', [AuthController::class, 'doLogoutUser']);

        $auth->post('/login', DoLoginAction::class);
    });

    $app->group('/test', function(RouteCollectorProxy $test) {
        $test->get('', [TestController::class, 'testResponse']);
    });
};
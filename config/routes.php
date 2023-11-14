<?php

declare(strict_types = 1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Http\Action\Auth\DoLoginAction;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Action\Auth\DoLogoutAction;
use App\Http\Action\Auth\ViewLoginAction;
use App\Http\Action\Auth\DoSignpostAction;
use App\Http\Action\Auth\DoTfaLoginAction;
use App\Http\Action\User\CreateUserAction;
use App\Http\Action\Actions\DoAddTaskAction;
use App\Http\Action\Auth\ViewTfaLoginAction;
use App\Http\Action\Dashboard\ViewDashboardAction;

return function(App $app) 
{
    $app->get('', DoSignpostAction::class);

    $app->group('/auth', function(RouteCollectorProxy $auth) {
        $auth->get('/login', ViewLoginAction::class);
        $auth->get('/twostep', ViewTfaLoginAction::class);
        $auth->get('/logout', DoLogoutAction::class);

        $auth->post('/login', DoLoginAction::class);
        $auth->post('/twostep', DoTfaLoginAction::class);
    });

    $app->group('/users', function(RouteCollectorProxy $users) {
        $users->get('/create', CreateUserAction::class);
    });

    $app->get('/dashboard', ViewDashboardAction::class)->add(AuthMiddleware::class);

    $app->group('/actions', function(RouteCollectorProxy $action) {
        $action->post('/add_new_task', DoAddTaskAction::class);
    });
};
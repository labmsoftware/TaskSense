<?php

declare(strict_types = 1);

use Slim\App;
use Monolog\Logger;
use Slim\Views\Twig;
use Doctrine\ORM\ORMSetup;
use Twig\Profiler\Profile;
use Odan\Session\PhpSession;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Idmarinas\TracyPanel\TwigBar;
use App\Support\Settings\Settings;
use Odan\Session\SessionInterface;
use App\Domain\Service\UserService;
use App\Authenticator\Authenticator;
use App\Domain\Service\AuthenticatorService;
use App\Handler\DefaultErrorHandler;
use Monolog\Formatter\LineFormatter;
use Slim\Middleware\ErrorMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Twig\Extension\ProfilerExtension;
use Monolog\Handler\RotatingFileHandler;
use Psr\Http\Message\UriFactoryInterface;
use Slim\Interfaces\RouteParserInterface;
use Selective\BasePath\BasePathMiddleware;
use Psr\Http\Message\StreamFactoryInterface;
use Idmarinas\TracyPanel\Twig\TracyExtension;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

return [

    // Application settings store
    Settings::class => function() {
        return new Settings(require __DIR__ . '/settings.php');
    },

    // Slim 4 App
    App::class => function(ContainerInterface $c) {
        $app = AppFactory::createFromContainer($c);

        (require __DIR__ . '/routes.php')($app);

        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },

    // PSR-17 HTTP Factories
    ResponseFactoryInterface::class => function(ContainerInterface $c) {
        return $c->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function(ContainerInterface $c) {
        return $c->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function(ContainerInterface $c) {
        return $c->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function(ContainerInterface $c) {
        return $c->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function(ContainerInterface $c) {
        return $c->get(Psr17Factory::class);
    },

    // Slim 4 Route Parser
    RouteParserInterface::class => function(ContainerInterface $c) {
        return $c->get(App::class)->getRouteCollector()->getRouteParser();
    },

    BasePathMiddleware::class => function(ContainerInterface $c) {
        return new BasePathMiddleware($c->get(App::class));
    },

    SessionManagerInterface::class => function(ContainerInterface $c) {
        return $c->get(SessionInterface::class);
    },

    SessionInterface::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);

        return new PhpSession($settings->get('session'));
    },

    // Doctrine EntityManager
    EntityManager::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);

        Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

        $orm_config = ORMSetup::createAttributeMetadataConfiguration(
            (array) $settings->get('doctrine.entity_dirs'),
            $settings->get('doctrine.dev_mode')
        );

        $connection = DriverManager::getConnection(
            $settings->get('doctrine.connection')
        );

        return new EntityManager($connection, $orm_config);
    },

    AuthenticatorService::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);

        return new AuthenticatorService(
            $c->get(EntityManager::class),
            $c->get(SessionInterface::class),
            $c->get(LoggerInterface::class),
            $settings->get('authenticator.crypto.algo'),
            $settings->get('authenticator.crypto.options')
        );
    },

    // Twig Template Rendering Engine
    Twig::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);

        $twig = Twig::create($settings->get('twig.templates'), [
            'debug' => $settings->get('twig.debug'),
            'cache' => $settings->get('twig.cache_dir'),
            'auto_reload' => $settings->get('twig.auto_reload')
        ]);

        if($settings->get('twig.debug')) {
            $profile = new Profile();
            $twig->getEnvironment()->addExtension(new ProfilerExtension($profile));

            $twig->getEnvironment()->addExtension(new TracyExtension());

            TwigBar::init($profile);
        }

        return $twig;

    },

    // Monolog Logger
    LoggerInterface::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);

        $logger = new Logger($settings->get('app_name'));

        if($settings->get('logger.path')) {
            $filename = sprintf('%s/application.log', $settings->get('logger.path'));
            $level = $settings->get('logger.level');
            $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level, true, 0777);
            $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));
            $logger->pushHandler($rotatingFileHandler);
        }

        return $logger;
    },

    // Error Handling
    ErrorMiddleware::class => function(ContainerInterface $c) {
        $settings = $c->get(Settings::class);
        $app = $c->get(App::class);

        $errorMidleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool) $settings->get('slim.display_error_details'),
            (bool) $settings->get('slim.log_errors'),
            (bool) $settings->get('slim.log_error_details')
        );

        $errorMidleware->setDefaultErrorHandler(DefaultErrorHandler::class);

        return $errorMidleware;
    },
];
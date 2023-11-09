<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Domain\Enum\AuthEnum;
use App\Domain\Service\AuthenticatorService;
use App\Renderer\RedirectRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    private AuthenticatorService $authService;
    private ResponseFactoryInterface $responseFactory;
    private RedirectRenderer $renderer;

    public function __construct(AuthenticatorService $authService, ResponseFactoryInterface $responseFactory, RedirectRenderer $renderer)
    {
        $this->authService = $authService;
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        if($this->authService->verify() == AuthEnum::AUTH_FAILED) {
            return $this->responseFactory->createResponse(302)->withHeader('Location', '/auth/login');
        }

        return $requestHandler->handle($request);
    }
}
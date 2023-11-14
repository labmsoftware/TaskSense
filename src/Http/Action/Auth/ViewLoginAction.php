<?php

declare(strict_types = 1);

namespace App\Http\Action\Auth;

use App\Domain\Service\AuthenticatorService;
use App\Domain\XferObject\UserCredentialsObject;
use Psr\Log\LoggerInterface;
use App\Renderer\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ViewLoginAction
{
    private TwigRenderer $renderer;
    private LoggerInterface $logger;
    private AuthenticatorService $authenticator;

    public function __construct(TwigRenderer $renderer, LoggerInterface $logger,  AuthenticatorService $authenticator)
    {
        $this->renderer = $renderer;
        $this->logger = $logger;
        $this->authenticator = $authenticator;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->renderer->template(
            $response,
            '/auth/login_password.twig',
            []
        );
    }
}
<?php

declare(strict_types = 1);

namespace App\Http\Action\Auth;

use Psr\Log\LoggerInterface;
use App\Domain\Enum\AuthEnum;
use App\Renderer\RedirectRenderer;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\AuthenticatorService;
use Psr\Http\Message\ServerRequestInterface;
use App\Domain\XferObject\UserCredentialsObject;

final class DoLoginAction
{
    private AuthenticatorService $authenticator;
    private RedirectRenderer $renderer;
    private LoggerInterface $logger;
    
    public function __construct(
        AuthenticatorService $authenticator,
        RedirectRenderer $renderer,
        LoggerInterface $logger
    ) {
        $this->authenticator = $authenticator;
        $this->renderer = $renderer;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $formData = $request->getParsedBody();

        $credentials = new UserCredentialsObject(
            $formData['username'],
            $formData['password']
        );

        $result = $this->authenticator->login($credentials);

        if($result == AuthEnum::AUTH_SUCCESS) {
            $this->logger->debug('redirect to dashboard');
            return $this->renderer->hxRedirect(
                $response,
                '/dashboard'
            );
        } elseif($result == AuthEnum::AUTH_FAILED) {
            return $this->renderer->hxRedirect(
                $response,
                '/auth/login'
            );
        }
    }
}
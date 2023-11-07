<?php

declare(strict_types = 1);

namespace App\Http\Action\Auth;

use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\AuthenticatorService;
use Psr\Http\Message\ServerRequestInterface;

final class DoLoginAction
{
    private AuthenticatorService $authenticator;
    private JsonRenderer $renderer;
    
    public function __construct(AuthenticatorService $authenticator, JsonRenderer $renderer)
    {
        $this->authenticator = $authenticator;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $formData = $request->getParsedBody();

        return $this->renderer->json($response, $formData);
    }
}
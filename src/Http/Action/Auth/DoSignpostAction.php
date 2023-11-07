<?php

declare(strict_types = 1);

namespace App\Http\Action\Auth;

use App\Renderer\RedirectRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DoSignpostAction
{
    private RedirectRenderer $renderer;

    public function __construct(RedirectRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->renderer->redirect(
            $response,
            '/login',
            []
        );
    }
}
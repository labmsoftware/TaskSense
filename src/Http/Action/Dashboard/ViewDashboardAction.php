<?php

declare(strict_types = 1);

namespace App\Http\Action\Dashboard;

use App\Renderer\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ViewDashboardAction
{
    private TwigRenderer $renderer;

    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->renderer->template(
            $response,
            '/dashboard/dashboard.twig',
            []
        );
    }
}
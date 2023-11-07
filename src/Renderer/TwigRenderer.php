<?php

declare(strict_types = 1);

namespace App\Renderer;

use Slim\Views\Twig;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class TwigRenderer
{
    private Twig $twig;

    public function __construct(ContainerInterface $c)
    {
        $this->twig = $c->get(Twig::class);
    }

    public function template(ResponseInterface $response, string $template, array $params = []): ResponseInterface
    {
        return $this->twig->render($response, $template, $params);
    }
}
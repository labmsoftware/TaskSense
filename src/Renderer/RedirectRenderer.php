<?php

declare(strict_types = 1);

namespace App\Renderer;

use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;

final class RedirectRenderer
{
    private RouteParserInterface $routeParser;

    public function __construct(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    /**
     * Prepares a HTTP response object to be returned to the client.
     *
     * @param ResponseInterface $response
     * @param string $dest
     * @param array $queryParams
     * 
     * @return ResponseInterface
     */
    public function redirect(ResponseInterface $response, string $dest, array $queryParams): ResponseInterface
    {
        if($queryParams) {
            $dest = sprintf('%s?%s', $dest, http_build_query($queryParams));
        }

        return $response->withStatus(302)->withHeader('Location', $dest);
    }
}
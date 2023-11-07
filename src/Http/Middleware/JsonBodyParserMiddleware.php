<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonBodyParserMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if(strstr($contentType, 'application/json')) {
            $contents = json_decode(file_get_contents('php://input'), true);
            
            if(json_last_error() == JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }

            return $requestHandler->handle($request);
        }
    }
}
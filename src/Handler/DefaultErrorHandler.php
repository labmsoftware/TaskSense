<?php

declare(strict_types = 1);

namespace App\Handler;

use Throwable;
use DomainException;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use App\Renderer\JsonRenderer;
use Slim\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class DefaultErrorHandler
{
    private ResponseFactoryInterface $responseFactory;
    private JsonRenderer $jsonRenderer;
    private LoggerInterface $logger;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        JsonRenderer $jsonRenderer,
        LoggerInterface $logger
    ) {
        $this->responseFactory = $responseFactory;
        $this->jsonRenderer = $jsonRenderer;
        $this->logger = $logger;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        
        if($logErrors) {
            $error = $this->getErrorDetails($exception, $logErrorDetails);
            $error['method'] = $request->getMethod();
            $error['url'] = (string) $request->getUri();

            $this->logger->error($exception->getMessage(), $error);
        }

        $response = $this->responseFactory->createResponse();

        $response = $this->jsonRenderer->json($response, [
            'error' => $this->getErrorDetails($exception, $displayErrorDetails)]
        );

        return $response->withStatus($this->getHttpStatusCode($exception));
    }

    private function getHttpStatusCode(Throwable $exception): int
    {
        $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        if($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
        }

        if($exception instanceof DomainException || $exception instanceof InvalidArgumentException) {
            $statusCode = StatusCodeInterface::STATUS_BAD_REQUEST;
        }

        $file = basename($exception->getFile());

        if($file === 'CallableResolver.php') {
            $statusCode = StatusCodeInterface::STATUS_NOT_FOUND;
        }

        return $statusCode;
    }

    public function getErrorDetails(Throwable $exception, bool $displayErrorDetails): array
    {
        if($displayErrorDetails === true) {
            return [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'previous' => $exception->getPrevious(),
                'trace' => $exception->getTrace()
            ];
        }

        return [
            'message' => $exception->getMessage()
        ];
    }
}
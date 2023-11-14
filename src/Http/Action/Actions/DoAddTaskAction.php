<?php
declare(strict_types=1);

namespace App\Http\Action\Actions;

use App\Renderer\TwigRenderer;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DoAddTaskAction
{
    private TwigRenderer $renderer;
    private LoggerInterface $logger;

    public function __construct(TwigRenderer $renderer, LoggerInterface $logger)
    {
        $this->renderer = $renderer;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        
    }


}
<?php
declare(strict_types=1);

namespace App\Http\Action\Actions;

use App\Domain\Service\ListService;
use App\Domain\XferObject\ListObject;
use App\Renderer\TwigRenderer;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DoAddTaskAction
{
    private TwigRenderer $renderer;
    private LoggerInterface $logger;
    private ListService $listService;

    public function __construct(TwigRenderer $renderer, LoggerInterface $logger, ListService $listService)
    {
        $this->renderer = $renderer;
        $this->logger = $logger;
        $this->listService = $listService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        
        $formData = $request->getParsedBody();
        $listData = new ListObject(
            $formData['title'],
            '',
            $formData['newTask']

            $

        );
    }


}
<?php

declare(strict_types = 1);

namespace App\Factory;

use Monolog\Logger;
use Psr\Log\LogLevel;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;
use App\Support\Settings\Settings;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;

final class LoggerFactory
{
    private string $path;
    private string $level;
    private array $handler;
    private ?HandlerInterface $test = null;

    public function __construct(Settings $settings)
    {
        $this->path = $settings->get('logger.path') ?? 'vfs://root/logs';
        $this->level = $settings->get('logger.level') ?? LogLevel::DEBUG;
        $this->test = $settings->get('logger.test') ?? null;
    }

    public function createLogger(string $name = null): LoggerInterface
    {
        if($this->test) {
            $this->handler = [$this->test];
        }

        $logger = new Logger($name ?? Uuid::uuid4());

        foreach($this->handler as $handler) {
            $logger->pushHandler($handler);
        }

        $this->handler = [];
        
        return $logger;
    }

    public function addHandler(HandlerInterface $handler): self
    {
        $this->handler[] = $handler;

        return $this;
    }

    public function addFileHandler(string $filename, LogLevel $level = null): self
    {
        $filename = sprintf('%s/$s', $this->path, $filename);
        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level ?? $this->level, true, 0777);

        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($rotatingFileHandler);

        return $this;
    }

    public function addConsoleHandler(LogLevel $level = null): self
    {
        $streamHandler = new StreamHandler('php://output', $level ?? $this->level);
        $streamHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($streamHandler);

        return $this;
    }
}
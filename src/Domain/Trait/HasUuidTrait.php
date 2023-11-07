<?php

declare(strict_types = 1);

namespace App\Domain\Trait;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Ramsey\Uuid\Rfc4122\UuidInterface;

trait HasUuidTrait
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(UuidGenerator::class)]
    private UuidInterface $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Domain\Trait\HasUuidTrait;
use App\Domain\Trait\HasCreatedUpdatedTrait;

#[Entity()]
#[Table(name: 'tickets')]
class TicketEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    #[Column(type: 'string', length: 50)]
    private string $title;

    #[Column(type: 'integer')]
    private int $status;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status = 0): self
    {
        $this->status = $status;

        return $this;
    }
}
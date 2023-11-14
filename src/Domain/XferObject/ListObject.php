<?php

declare(strict_types = 1);

namespace App\Domain\XferObject;

use App\Domain\Entity\UserEntity;

class ListObject
{
    public string $title;
    public ?UserEntity $ownerId;
    public ?array $tasks;

    public function __construct(
        string $title,
        UserEntity $ownerId = null,
        array $tasks = null
    )
    {
        $this->title = $title;
        $this->ownerId = $ownerId;
        $this->tasks = $tasks;
    }
}
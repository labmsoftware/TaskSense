<?php

declare(strict_types = 1);

namespace App\Domain\XferObject;

class ListObject
{
    public string $title;
    public string $owner;
    public ?array $tasks;
// Dont forget to set email in prod
    public function __construct(
        string $title,
        string $owner,
        array $tasks = null
    )
    {
        $this->title = $title;
        $this->owner = $owner;
        $this->tasks = $tasks;
    }
}
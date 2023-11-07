<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Trait\HasCreatedUpdatedTrait;
use App\Domain\Trait\HasUuidTrait;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table(name: 'comments')]
class CommentEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    private string $comment;

    private UserEntity $author;
}
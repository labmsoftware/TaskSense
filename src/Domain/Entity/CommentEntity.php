<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table(name: 'comments')]
class CommentEntity
{
    private string $comment;

    private UserEntity $author;
}
<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Domain\Trait\HasUuidTrait;
use Doctrine\ORM\Mapping\ManyToOne;
use App\Domain\Trait\HasCreatedUpdatedTrait;
use App\Domain\Repository\AuthTokenRepository;

#[Entity(repositoryClass: AuthTokenRepository::class)]
#[Table(name: 'auth_tokens')]
class AuthTokenEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    #[Column(type: 'string', length: 64)]
    private string $hash;

    #[ManyToOne(targetEntity: UserEntity::class, inversedBy: 'auth_tokens', cascade: ['PERSIST', 'MERGE', 'REMOVE'])]
    private UserEntity $owner;

    #[Column(type: 'datetime', updatable: false)]
    private DateTime $expires;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getOwner(): UserEntity
    {
        return $this->owner;
    }

    public function setOwner(UserEntity $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getExpires(): DateTime
    {
        return $this->expires;
    }

    public function setExpires(DateTime $expires): self
    {
        $this->expires = $expires;

        return $this;
    }
}
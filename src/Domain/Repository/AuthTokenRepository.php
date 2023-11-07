<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use Doctrine\ORM\EntityRepository;
use App\Domain\Entity\AuthTokenEntity;

class AuthTokenRepository extends EntityRepository
{
    public function findByHash(string $hash): ?AuthTokenEntity
    {
        return $this->findOneBy(['hash', $hash]);
    }
}
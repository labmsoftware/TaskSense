<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use Carbon\Carbon;
use App\Domain\Entity\UserEntity;
use Doctrine\ORM\EntityRepository;
use App\Domain\Entity\AuthTokenEntity;

class AuthTokenRepository extends EntityRepository
{
    public function create(UserEntity $user): AuthTokenEntity
    {
        $dt = new Carbon('now');

        $token = (new AuthTokenEntity())
            ->setOwner($user)
            ->setCreated($dt)
            ->setUpdated($dt)
            ->setExpires((new Carbon('now'))->addMinutes(15));

        $this->_em->persist($token);
        $this->_em->flush();

        return $token;
    }

    public function delete(AuthTokenEntity $token): self
    {
        $this->_em->remove($token);
        $this->_em->flush();

        return $this;
    }
}
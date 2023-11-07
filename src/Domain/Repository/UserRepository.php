<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\UserEntity;
use Doctrine\ORM\EntityRepository;
use App\Domain\XferObject\UserCredentialsObject;

class UserRepository extends EntityRepository
{
    public function new(UserCredentialsObject $credentials): UserEntity
    {
        $user = (new UserEntity())
            ->setUsername($credentials->username)
            ->setPassword($credentials->password)
            ->setEmail($credentials->email)
            ->setGivenName($credentials->given_name)
            ->setFamilyName($credentials->family_name);

        return $user;
    }

    public function save(UserEntity $user): void
    {
        $this->_em->persist($user);

        $this->_em->flush();
    }

    public function get(array $criteria): ?UserEntity
    {
        return $this->findOneBy($criteria);
    }
}
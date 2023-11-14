<?php

declare(strict_types = 1);

namespace App\Domain\Service;

use Doctrine\ORM\EntityManager;
use App\Domain\Entity\UserEntity;
use App\Domain\Repository\UserRepository;
use App\Domain\XferObject\UserCredentialsObject;

/**
 * Service for handling UserEntity CRUD operations.
 */
final class UserService
{
    private UserRepository $users;
    private CryptographyService $cryptoService;

    public function __construct(
        EntityManager $em,
        CryptographyService $cryptoService
    ) {
        $this->users = $em->getRepository(UserEntity::class);
        $this->cryptoService = $cryptoService;
    }

    /**
     * Create and persist a new user.
     *
     * @param UserCredentialsObject $userCredentialsObject
     * 
     * @return self
     */
    public function createUser(UserCredentialsObject $credentials): self
    {
        $hashedPassword = $this->cryptoService->createPasswordHash($credentials->password);
        $credentials->password = $hashedPassword;

        $user = $this->users->newUser($credentials);
        $this->users->persitNewUser($user);

        return $this;
    }

    /**
     * Attempts to find a user by their Id.
     *
     * @param string $id
     * 
     * @return UserEntity|null
     */
    public function getUser(string $id): ?UserEntity
    {
        return $this->users->findOneBy(['id' => $id]);
    }
}
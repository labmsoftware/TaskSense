<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Domain\Entity\UserEntity;
use App\Domain\Trait\HasUuidTrait;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use App\Domain\Entity\AuthTokenEntity;
use Doctrine\Common\Collections\Collection;
use App\Domain\Trait\HasCreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity()]
#[Table(name: 'customers')]
class CustomerEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    #[Column(type: 'string', length: 24)]
    private string $username;

    #[Column(type: 'string')]
    private string $password;

    #[Column(type: 'string', length: 320)]
    private string $email;

    #[Column(type: 'string')]
    private string $mobile_number;

    #[Column(type: 'string')]
    private string $given_name;

    #[Column(type: 'string')]
    private string $family_name;

    #[ManyToOne(targetEntity: UserEntity::class, inversedBy: 'customers', cascade: ['PERSIST', 'MERGE'])]
    private UserEntity $created_by;

    #[OneToMany(targetEntity: AuthTokenEntity::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $auth_tokens;

    public function __construct()
    {
        $this->auth_tokens = new ArrayCollection();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGivenName(): string
    {
        return $this->given_name;
    }

    public function setGivenName(string $given_name): self
    {
        $this->given_name = $given_name;

        return $this;
    }

    public function getFamilyName(): string
    {
        return $this->family_name;
    }

    public function setFamilyName(string $family_name): self
    {
        $this->family_name = $family_name;

        return $this;
    }

    /**
     * Assigns an auth token to a user.
     *
     * @param AuthTokenEntity $auth_token
     * @return self
     */
    public function addAuthToken(AuthTokenEntity $auth_token): self
    {
        $this->auth_tokens->add($auth_token);

        return $this;
    }

    /**
     * Removes an auth token from a user.
     *
     * @param string $id
     * @return self
     */
    public function removeAuthToken(string $id): self
    {
        $this->auth_tokens->remove($id);

        return $this;
    }
}
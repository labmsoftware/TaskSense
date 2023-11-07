<?php

declare(strict_types = 1);

namespace App\Domain\XferObject;

class UserCredentialsObject
{
    public string $username;
    public string $password;
    public string $email;
    public string $given_name;
    public string $family_name;

    public function __construct(
        string $username,
        string $password,
        string $email,
        string $given_name,
        string $family_name
    )
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->given_name = $given_name;
        $this->family_name = $family_name;
    }
}
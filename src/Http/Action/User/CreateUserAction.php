<?php

declare(strict_types = 1);

namespace App\Http\Action\User;

use App\Domain\Service\AuthenticatorService;
use App\Domain\XferObject\UserCredentialsObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateUserAction
{
    private AuthenticatorService $auth;

    public function __construct(AuthenticatorService $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $credentials = new UserCredentialsObject(
            'benjamin',
            'hello',
            'benmoss2002@fastmail.co.uk',
            'Benjamin',
            'Moss'
        );

        $this->auth->createUser($credentials);
    }
}
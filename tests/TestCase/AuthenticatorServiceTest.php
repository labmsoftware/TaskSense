<?php
declare (strict_types=1);

use App\Domain\Service\AuthenticatorService;
use PHPUnit\Framework\TestCase;
use App\Test\Traits\AppTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;

final class AuthenticatorServiceTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testLoginWithString(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/login',
            [
                'username' => 'louis',
                'password' => 'hello'
            ]
        );

        $response = $this->app->handle($request);

        $this->assertSame()
    }
}
<?php

declare(strict_types = 1);

namespace App\Domain\Service;

use Carbon\Carbon;
use BaconQrCode\Writer;
use Psr\Log\LoggerInterface;
use App\Domain\Enum\AuthEnum;
use Doctrine\ORM\EntityManager;
use RobThree\Auth\TwoFactorAuth;
use App\Domain\Entity\UserEntity;
use Odan\Session\SessionInterface;
use App\Domain\Entity\AuthTokenEntity;
use BaconQrCode\Renderer\ImageRenderer;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\CryptographyService;
use App\Domain\Repository\AuthTokenRepository;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use App\Domain\XferObject\UserCredentialsObject;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

final class AuthenticatorService
{
    private UserRepository $users;
    private AuthTokenRepository $tokens;
    private CryptographyService $cryptoService;
    private TwoFactorAuth $twoFactorAuth;
    private SessionInterface $session;
    private LoggerInterface $logger;
    private string $qrCodePath;

    public function __construct(
        CryptographyService $cryptoService,
        EntityManager $em,
        SessionInterface $session,
        TwoFactorAuth $twoFactorAuth,
        LoggerInterface $logger,
        string $qrCodePath
    ) {
        $this->users = $em->getRepository(UserEntity::class);
        $this->tokens = $em->getRepository(AuthTokenEntity::class);
        $this->cryptoService = $cryptoService;
        $this->twoFactorAuth = $twoFactorAuth;
        $this->session = $session;
        $this->logger = $logger;
        $this->qrCodePath = $qrCodePath;
    }

    /**
     * Attempts to authorize a user with the provided credentials.
     *
     * @param UserCredentialsObject $credentials
     * 
     * @return AuthEnum
     */
    public function login(UserCredentialsObject $credentials): AuthEnum
    {
        $this->clearSessionStorage();

        $user = $this->users->findOneBy(['username' => $credentials->username]);


        if($user == null || !password_verify($credentials->password, $user->getPassword())) {
            $this->logger->debug('A user failed to authenticate.', [$user, $credentials->password, $user->getPassword]);

            return AuthEnum::AUTH_FAILED;
        }

        $this->session->set('zenrepair_user', base64_encode($user->getId()));

        /** Redirects the user if TFA is required. */
        if($user->getSecret) {
            return AuthEnum::AUTH_TWOFACTOR;
        }

        $token = $this->tokens->create($user);

        $this->session->set('zenrepair_session_auth', base64_encode($token->getId()));

        return AuthEnum::AUTH_SUCCESS;
    }

    /**
     * Authenticates the user using their MFA code.
     *
     * @param UserCredentialsObject $credentials
     * @param string $code
     * 
     * @return AuthEnum
     */
    public function loginTfa(string $code): AuthEnum
    {
        $decodedData = $this->cryptoService->sessionDataDecoder([
            'zenrepair_user' => $this->session->get('zenrepair_user')
        ]);

        $user = $this->users->findOneBy(['id' => $decodedData['zenrepair_user']]);

        $this->logger->debug('Attempting Two-Factor Authentication with code');

        if(!$user || !$this->twoFactorAuth->verifyCode($user->getSecret(), $code)) {
            $this->logger->debug('Two-Factor Authenticaton Failed.');

            $this->clearSessionStorage();

            return AuthEnum::AUTH_FAILED;
        }

        $token = $this->tokens->create($user);

        $encodedData = $this->cryptoService->sessionDataEncoder([
            'zenrepair_session_auth' => $token->getId(),
        ]);

        $this->session->set('zenrepair_session_auth', $encodedData['zenrepair_session_auth']);

        return AuthEnum::AUTH_SUCCESS;
    }

    /**
     * Attemps to verify a user's authorization status using the stored authorization token.
     *
     * @return AuthEnum
     */
    public function verify(): AuthEnum
    {
        if(!$this->session->has('zenrepair_session_auth')) {
            return AuthEnum::AUTH_FAILED;
        }

        $encodedTokenId = $this->session->get('zenrepair_session_auth');
        $encodedUserId = $this->session->get('zenrepair_user');

        $decodedData = $this->cryptoService->sessionDataDecoder([
            'zenrepair_session_auth' => $encodedTokenId,
            'zenrepair_user' => $encodedUserId
        ]);

        $token = $this->tokens->findOneBy(['id' => $decodedData['zenrepair_session_auth']]);
        $user = $this->users->findOneBy(['id' => $decodedData['zenrepair_user']]);

        if($token == null 
            || $token->getExpires()->lessThanOrEqualTo(new Carbon('now')) 
            || !$token->getOwner() == $user) {
                
            $this->clearSessionStorage();

            return AuthEnum::AUTH_FAILED;
        }

        return AuthEnum::AUTH_SUCCESS;
    }

    public function logout(): void
    {
        $encodedTokenId = $this->session->get('zenrepair_session_auth');

        $decodedData = $this->cryptoService->sessionDataDecoder([
            'zenrepair_session_auth' => $encodedTokenId
        ]);

        $token = $this->tokens->findOneBy(['id' => $decodedData['zenrepair_session_auth']]);

        $this->tokens->delete($token);
    }

    public function createUser(UserCredentialsObject $credentials): void
    {
        $hashedPassword = $this->cryptoService->createPasswordHash($credentials->password);
        $credentials->password = $hashedPassword;

        $user = $this->users->new($credentials);

        $this->users->save($user);
    }

    public function addTfaSecret(string $userId): void
    {
        $user = $this->users->findOneBy(['id' => $userId]);

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $writer->writeFile('Hello World!', sprintf('%s/%s', $this->qrCodePath, $userId));

        $this->users->addTfaSecret($user, $this->twoFactorAuth->createSecret());
    }

     /**
     * Clears the client's authorization storage.
     *
     * @return void
     */
    private function clearSessionStorage(): void
    {
        if($this->session->has('authStorage')) {
            $this->session->delete('authStorage');
        }
    }
}
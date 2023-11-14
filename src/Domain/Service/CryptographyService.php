<?php

declare(strict_types = 1);

namespace App\Domain\Service;

/**
 * Dedicated cryptography service.
 */
final class CryptographyService
{
    private string $algorithm;
    private array $options;

    public function __construct(
        string $algorithm = PASSWORD_ARGON2ID,
        array $options = null
    ) {
        $this->algorithm = $algorithm;
        $this->options = $options ?? [
            'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
        ];
    }

    /**
     * Creates a password hash from the provided password.
     *
     * @param string $password
     * @return string
     */
    public function createPasswordHash(string $password): string
    {
        return password_hash(
            $password,
            $this->algorithm,
            $this->options
        );
    }

    /**
     * Encodes the given data for storage in the client session.
     *
     * @param array $data
     * 
     * @return array
     */
    public function sessionDataEncoder(array $data): array
    {
        $encodedData = [];

        foreach($data as $key => $value) {
            $encodedData[$key] = base64_encode($value);
        }

        return $encodedData;
    }

    /**
     * Decodes the given data for storage in the client session.
     *
     * @param array $data
     * 
     * @return array
     */
    public function sessionDataDecoder(array $data): array
    {
        $decodedData = [];

        foreach($data as $key => $value) {
            $decodedData[$key] = base64_decode($value);
        }

        return $decodedData;
    }
}
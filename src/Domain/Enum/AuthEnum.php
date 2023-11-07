<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

enum AuthEnum
{
    case AUTH_SUCCESS;
    case AUTH_VERIFIED;
    case AUTH_FAILED;
    case AUTH_TWOFACTOR;
}
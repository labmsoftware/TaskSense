<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

enum UserEnum
{
    case CreationSuccess;
    case CreationFail;
}
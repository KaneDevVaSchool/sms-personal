<?php

namespace App\Enums;

enum ErrorCode: string
{
    case AuthUnauthorized = 'AUTH_401';
    case UserNotFound = 'USR_404';
    case Forbidden = 'SYS_403';
    case ValidationFailed = 'INVALID_INPUT';
    case ServerError = 'SYS_500';
}

<?php

namespace App\Enums;

enum AppRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Member = 'member';
}

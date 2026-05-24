<?php

namespace App\Policies;

class UserPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'team';
    }
}

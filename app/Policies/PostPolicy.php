<?php

namespace App\Policies;

class PostPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'knowledge';
    }
}

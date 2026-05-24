<?php

namespace App\Policies;

class ResourcePolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'resources';
    }
}

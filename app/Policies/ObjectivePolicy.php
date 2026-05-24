<?php

namespace App\Policies;

class ObjectivePolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'okrs';
    }
}

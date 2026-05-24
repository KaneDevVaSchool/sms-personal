<?php

namespace App\Policies;

class ProjectPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'projects';
    }
}

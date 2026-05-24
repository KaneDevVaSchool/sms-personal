<?php

namespace App\Policies;

class DepartmentPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'team';
    }
}

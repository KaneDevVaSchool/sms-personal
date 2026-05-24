<?php

namespace App\Policies;

class WebsitePolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'websites';
    }
}

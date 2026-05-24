<?php

namespace App\Policies;

class AiAccountPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'ai';
    }
}

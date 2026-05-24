<?php

namespace App\Policies;

class ContractPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'contracts';
    }
}

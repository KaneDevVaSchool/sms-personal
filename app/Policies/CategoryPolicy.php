<?php

namespace App\Policies;

/**
 * Shares knowledge.* permissions with Post (categories are part of the knowledge base UI).
 */
class CategoryPolicy
{
    use ChecksOpsPermissions;

    protected static function opsPermissionPrefix(): string
    {
        return 'knowledge';
    }
}

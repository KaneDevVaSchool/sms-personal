<?php

namespace App\Policies;

use App\Models\User;

trait ChecksOpsPermissions
{
    /** Permission name prefix in Spatie (e.g. "team", "resources"). */
    abstract protected static function opsPermissionPrefix(): string;

    protected function canOps(User $user, string $action): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        $prefix = static::opsPermissionPrefix();

        if (in_array($action, ['create', 'update', 'delete'], true)) {
            return $user->can("{$prefix}.manage");
        }

        return $user->can("{$prefix}.view");
    }

    public function viewAny(User $user): bool
    {
        return $this->canOps($user, 'view');
    }

    public function view(User $user, mixed $model): bool
    {
        return $this->canOps($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->canOps($user, 'create');
    }

    public function update(User $user, mixed $model): bool
    {
        return $this->canOps($user, 'update');
    }

    public function delete(User $user, mixed $model): bool
    {
        return $this->canOps($user, 'delete');
    }

    public function restore(User $user, mixed $model): bool
    {
        return $this->delete($user, $model);
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return $this->delete($user, $model);
    }
}

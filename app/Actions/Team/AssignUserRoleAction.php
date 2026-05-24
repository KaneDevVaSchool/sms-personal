<?php

namespace App\Actions\Team;

use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignUserRoleAction
{
    public function execute(User $user, string $roleName): User
    {
        $role = Role::findByName($roleName, 'web');
        $user->syncRoles([$role]);

        return $user->fresh(['roles', 'department']);
    }
}

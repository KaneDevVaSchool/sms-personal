<?php

namespace Database\Seeders;

use App\Enums\AppRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'team.view', 'team.manage',
            'resources.view', 'resources.manage',
            'websites.view', 'websites.manage',
            'projects.view', 'projects.manage',
            'knowledge.view', 'knowledge.manage',
            'contracts.view', 'contracts.manage',
            'ai.view', 'ai.manage',
            'okrs.view', 'okrs.manage',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::findOrCreate(AppRole::Admin->value, 'web');
        $manager = Role::findOrCreate(AppRole::Manager->value, 'web');
        $member = Role::findOrCreate(AppRole::Member->value, 'web');

        $admin->syncPermissions($permissions);

        $manager->syncPermissions([
            'team.view', 'team.manage',
            'resources.view', 'resources.manage',
            'websites.view', 'websites.manage',
            'projects.view', 'projects.manage',
            'knowledge.view', 'knowledge.manage',
            'contracts.view', 'contracts.manage',
            'ai.view', 'ai.manage',
            'okrs.view', 'okrs.manage',
        ]);

        $member->syncPermissions([
            'team.view',
            'resources.view',
            'websites.view',
            'projects.view',
            'knowledge.view',
            'contracts.view',
            'ai.view',
            'okrs.view',
        ]);

        $adminUser = User::query()->firstOrCreate(
            ['email' => 'admin@ops.local'],
            ['name' => 'OPS Admin', 'password' => 'password', 'email_verified_at' => now()],
        );
        $adminUser->assignRole(AppRole::Admin->value);
    }
}

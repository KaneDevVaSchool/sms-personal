<?php

use App\Models\Department;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('team users index requires authentication', function () {
    $this->get(route('team.users.index'))->assertRedirect(route('login'));
});

test('admin can list team users', function () {
    $admin = User::query()->where('email', 'admin@ops.local')->first();

    $this->actingAs($admin)
        ->get(route('team.users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('users'));
});

test('admin can create and update department', function () {
    $admin = User::query()->where('email', 'admin@ops.local')->first();

    $this->actingAs($admin)
        ->post(route('team.departments.store'), ['name' => 'Engineering'])
        ->assertRedirect(route('team.departments.index'));

    $department = Department::query()->where('name', 'Engineering')->first();
    expect($department)->not->toBeNull();

    $this->actingAs($admin)
        ->patch(route('team.departments.update', $department), ['name' => 'Eng'])
        ->assertRedirect(route('team.departments.edit', $department));

    expect($department->fresh()->name)->toBe('Eng');
});

test('admin can update user department and roles', function () {
    $admin = User::query()->where('email', 'admin@ops.local')->first();
    $member = User::factory()->create();

    $department = Department::query()->create(['name' => 'Ops']);

    $this->actingAs($admin)
        ->patch(route('team.users.update', $member), [
            'department_id' => $department->id,
            'roles' => ['member'],
        ])
        ->assertRedirect(route('team.users.show', $member));

    $member->refresh();
    expect($member->department_id)->toBe($department->id);
    expect($member->hasRole('member'))->toBeTrue();
});

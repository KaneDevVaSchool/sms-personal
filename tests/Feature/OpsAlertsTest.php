<?php

use App\Jobs\RunDailyOpsAlerts;
use App\Models\Resource;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

uses(RefreshDatabase::class);

test('daily ops alerts job can be dispatched', function () {
    Bus::fake();

    RunDailyOpsAlerts::dispatch();

    Bus::assertDispatched(RunDailyOpsAlerts::class);
});

test('resource index supports status filter for authorized user', function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $admin = User::query()->where('email', 'admin@ops.local')->first();

    Resource::query()->create(['name' => 'Active VPS', 'status' => 'active']);
    Resource::query()->create(['name' => 'Expired Domain', 'status' => 'expired']);

    $this->actingAs($admin)
        ->get(route('resources.index', ['status' => 'active']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('resources'));
});

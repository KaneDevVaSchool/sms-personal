<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\UpdateTeamUserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TeamUserController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->with(['department:id,name', 'roles:id,name'])
            ->orderBy('name')
            ->paginate(20);

        $departments = Department::query()->orderBy('name')->get(['id', 'name']);

        return Inertia::render('team/pages/UserIndex', [
            'users' => $users,
            'departments' => $departments,
        ]);
    }

    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        $user->load(['department:id,name', 'roles:id,name']);

        return Inertia::render('team/pages/UserShow', [
            'user' => $user,
            'departments' => Department::query()->orderBy('name')->get(['id', 'name']),
            'roleOptions' => ['admin', 'manager', 'member'],
        ]);
    }

    public function update(UpdateTeamUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        $user->update([
            'department_id' => $data['department_id'] ?? null,
        ]);

        if (array_key_exists('roles', $data)) {
            $user->syncRoles($data['roles'] ?? []);
        }

        return redirect()->route('team.users.show', $user)
            ->with('flash', ['message' => 'User updated.']);
    }
}

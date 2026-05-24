<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Department::class);

        $departments = Department::query()
            ->with('manager:id,name')
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('team/pages/DepartmentIndex', [
            'departments' => $departments,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Department::class);

        return Inertia::render('team/pages/DepartmentCreate', [
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Department::class);

        Department::query()->create($request->validated());

        return redirect()->route('team.departments.index')
            ->with('flash', ['message' => 'Department created.']);
    }

    public function edit(Department $department): Response
    {
        $this->authorize('update', $department);

        return Inertia::render('team/pages/DepartmentEdit', [
            'department' => $department,
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->authorize('update', $department);

        $department->update($request->validated());

        return redirect()->route('team.departments.edit', $department)
            ->with('flash', ['message' => 'Department updated.']);
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('delete', $department);

        $department->delete();

        return redirect()->route('team.departments.index')
            ->with('flash', ['message' => 'Department deleted.']);
    }
}

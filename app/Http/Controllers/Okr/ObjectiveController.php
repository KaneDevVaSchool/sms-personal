<?php

namespace App\Http\Controllers\Okr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Okr\StoreObjectiveRequest;
use App\Http\Requests\Okr\UpdateObjectiveRequest;
use App\Models\Objective;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ObjectiveController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Objective::class);

        $objectives = Objective::query()
            ->with(['team:id,name', 'owner:id,name'])
            ->withCount('keyResults')
            ->orderByDesc('year')
            ->orderByDesc('quarter')
            ->paginate(20);

        return Inertia::render('okrs/pages/ObjectiveIndex', [
            'objectives' => $objectives,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Objective::class);

        return Inertia::render('okrs/pages/ObjectiveCreate', [
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreObjectiveRequest $request): RedirectResponse
    {
        $this->authorize('create', Objective::class);

        Objective::query()->create($request->validated());

        return redirect()->route('objectives.index');
    }

    public function show(Objective $objective): Response
    {
        $this->authorize('view', $objective);

        $objective->load(['team:id,name', 'owner:id,name', 'keyResults.checkins']);

        return Inertia::render('okrs/pages/ObjectiveShow', [
            'objective' => $objective,
        ]);
    }

    public function edit(Objective $objective): Response
    {
        $this->authorize('update', $objective);

        return Inertia::render('okrs/pages/ObjectiveEdit', [
            'objective' => $objective,
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateObjectiveRequest $request, Objective $objective): RedirectResponse
    {
        $this->authorize('update', $objective);

        $objective->update($request->validated());

        return redirect()->route('objectives.show', $objective);
    }

    public function destroy(Objective $objective): RedirectResponse
    {
        $this->authorize('delete', $objective);

        $objective->delete();

        return redirect()->route('objectives.index');
    }
}

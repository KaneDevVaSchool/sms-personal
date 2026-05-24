<?php

namespace App\Http\Controllers\Okr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Okr\StoreKeyResultRequest;
use App\Http\Requests\Okr\UpdateKeyResultRequest;
use App\Models\KeyResult;
use App\Models\Objective;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class KeyResultController extends Controller
{
    public function index(Objective $objective): Response
    {
        $this->authorize('view', $objective);

        $items = KeyResult::query()
            ->where('objective_id', $objective->id)
            ->withCount('checkins')
            ->orderBy('id')
            ->paginate(30);

        return Inertia::render('okrs/pages/KeyResultIndex', [
            'objective' => $objective,
            'keyResults' => $items,
        ]);
    }

    public function create(Objective $objective): Response
    {
        $this->authorize('update', $objective);

        return Inertia::render('okrs/pages/KeyResultCreate', [
            'objective' => $objective,
        ]);
    }

    public function store(StoreKeyResultRequest $request, Objective $objective): RedirectResponse
    {
        $this->authorize('update', $objective);

        KeyResult::query()->create(array_merge($request->validated(), [
            'objective_id' => $objective->id,
        ]));

        return redirect()->route('objectives.key-results.index', $objective);
    }

    public function edit(Objective $objective, KeyResult $key_result): Response
    {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('update', $objective);

        return Inertia::render('okrs/pages/KeyResultEdit', [
            'objective' => $objective,
            'keyResult' => $key_result,
        ]);
    }

    public function update(
        UpdateKeyResultRequest $request,
        Objective $objective,
        KeyResult $key_result,
    ): RedirectResponse {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('update', $objective);

        $key_result->update($request->validated());

        return redirect()->route('objectives.key-results.index', $objective);
    }

    public function destroy(Objective $objective, KeyResult $key_result): RedirectResponse
    {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('update', $objective);

        $key_result->delete();

        return redirect()->route('objectives.key-results.index', $objective);
    }
}

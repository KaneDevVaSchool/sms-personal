<?php

namespace App\Http\Controllers\Okr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Okr\StoreOkrCheckinRequest;
use App\Http\Requests\Okr\UpdateOkrCheckinRequest;
use App\Models\KeyResult;
use App\Models\Objective;
use App\Models\OkrCheckin;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OkrCheckinController extends Controller
{
    public function index(Objective $objective, KeyResult $key_result): Response
    {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('view', $objective);

        $checkins = OkrCheckin::query()
            ->where('key_result_id', $key_result->id)
            ->with('user:id,name')
            ->orderByDesc('checked_at')
            ->paginate(30);

        return Inertia::render('okrs/pages/CheckinIndex', [
            'objective' => $objective,
            'keyResult' => $key_result,
            'checkins' => $checkins,
        ]);
    }

    public function create(Objective $objective, KeyResult $key_result): Response
    {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('update', $objective);

        return Inertia::render('okrs/pages/CheckinCreate', [
            'objective' => $objective,
            'keyResult' => $key_result,
        ]);
    }

    public function store(
        StoreOkrCheckinRequest $request,
        Objective $objective,
        KeyResult $key_result,
    ): RedirectResponse {
        abort_unless($key_result->objective_id === $objective->id, 404);

        $this->authorize('update', $objective);

        $validated = $request->validated();

        OkrCheckin::query()->create([
            ...$validated,
            'key_result_id' => $key_result->id,
            'user_id' => $request->user()->id,
            'checked_at' => $validated['checked_at'] ?? now(),
        ]);

        return redirect()->route(
            'objectives.key-results.checkins.index',
            [$objective, $key_result],
        );
    }

    public function edit(Objective $objective, KeyResult $key_result, OkrCheckin $checkin): Response
    {
        $this->authorizeCheckin($objective, $key_result, $checkin);

        return Inertia::render('okrs/pages/CheckinEdit', [
            'objective' => $objective,
            'keyResult' => $key_result,
            'checkin' => $checkin,
        ]);
    }

    public function update(
        UpdateOkrCheckinRequest $request,
        Objective $objective,
        KeyResult $key_result,
        OkrCheckin $checkin,
    ): RedirectResponse {
        $this->authorizeCheckin($objective, $key_result, $checkin);

        $checkin->update($request->validated());

        return redirect()->route(
            'objectives.key-results.checkins.index',
            [$objective, $key_result],
        );
    }

    public function destroy(
        Objective $objective,
        KeyResult $key_result,
        OkrCheckin $checkin,
    ): RedirectResponse {
        $this->authorizeCheckin($objective, $key_result, $checkin);

        $checkin->delete();

        return redirect()->route(
            'objectives.key-results.checkins.index',
            [$objective, $key_result],
        );
    }

    protected function authorizeCheckin(Objective $objective, KeyResult $key_result, OkrCheckin $checkin): void
    {
        abort_unless($key_result->objective_id === $objective->id, 404);
        abort_unless($checkin->key_result_id === $key_result->id, 404);

        $this->authorize('update', $objective);
    }
}

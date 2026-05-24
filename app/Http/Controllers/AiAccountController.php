<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiAccountRequest;
use App\Http\Requests\UpdateAiAccountRequest;
use App\Models\AiAccount;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AiAccountController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', AiAccount::class);

        $accounts = AiAccount::query()
            ->with(['team:id,name', 'project:id,name'])
            ->orderByDesc('updated_at')
            ->paginate(20);

        $accounts->through(fn (AiAccount $account) => $this->sanitize($account));

        return Inertia::render('ai-accounts/pages/AiAccountIndex', [
            'accounts' => $accounts,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', AiAccount::class);

        return Inertia::render('ai-accounts/pages/AiAccountCreate', [
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreAiAccountRequest $request): RedirectResponse
    {
        $this->authorize('create', AiAccount::class);

        AiAccount::query()->create($this->payloadFromRequest($request->validated()));

        return redirect()->route('ai-accounts.index');
    }

    public function show(AiAccount $aiAccount): Response
    {
        $this->authorize('view', $aiAccount);

        $aiAccount->load(['team:id,name', 'project:id,name']);

        return Inertia::render('ai-accounts/pages/AiAccountShow', [
            'account' => $this->sanitize($aiAccount),
        ]);
    }

    public function edit(AiAccount $aiAccount): Response
    {
        $this->authorize('update', $aiAccount);

        return Inertia::render('ai-accounts/pages/AiAccountEdit', [
            'account' => $this->sanitize($aiAccount),
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateAiAccountRequest $request, AiAccount $aiAccount): RedirectResponse
    {
        $this->authorize('update', $aiAccount);

        $aiAccount->update($this->payloadFromRequest($request->validated()));

        return redirect()->route('ai-accounts.show', $aiAccount);
    }

    public function destroy(AiAccount $aiAccount): RedirectResponse
    {
        $this->authorize('delete', $aiAccount);

        $aiAccount->delete();

        return redirect()->route('ai-accounts.index');
    }

    protected function sanitize(AiAccount $account): AiAccount
    {
        $account->makeHidden(['api_key_encrypted']);

        return $account;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function payloadFromRequest(array $data): array
    {
        if (array_key_exists('api_key', $data)) {
            $key = $data['api_key'];
            unset($data['api_key']);
            if ($key !== null && $key !== '') {
                // Plaintext; model `encrypted` cast handles storage.
                $data['api_key_encrypted'] = $key;
            }
        }

        return $data;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contract\StoreContractFileRequest;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Http\Requests\Contract\UpdateContractRequest;
use App\Models\Contract;
use App\Models\ContractFile;
use App\Services\StorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ContractController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Contract::class);

        $contracts = Contract::query()
            ->withCount('files')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return Inertia::render('contracts/pages/ContractIndex', [
            'contracts' => $contracts,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Contract::class);

        return Inertia::render('contracts/pages/ContractCreate');
    }

    public function store(StoreContractRequest $request): RedirectResponse
    {
        $this->authorize('create', Contract::class);

        Contract::query()->create($request->validated());

        return redirect()->route('contracts.index');
    }

    public function show(Contract $contract): Response
    {
        $this->authorize('view', $contract);

        $contract->load('files');

        return Inertia::render('contracts/pages/ContractShow', [
            'contract' => $contract,
        ]);
    }

    public function edit(Contract $contract): Response
    {
        $this->authorize('update', $contract);

        return Inertia::render('contracts/pages/ContractEdit', [
            'contract' => $contract,
        ]);
    }

    public function update(UpdateContractRequest $request, Contract $contract): RedirectResponse
    {
        $this->authorize('update', $contract);

        $contract->update($request->validated());

        return redirect()->route('contracts.show', $contract);
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $this->authorize('delete', $contract);

        $contract->delete();

        return redirect()->route('contracts.index');
    }

    public function storeFile(StoreContractFileRequest $request, Contract $contract, StorageService $storage): RedirectResponse
    {
        $this->authorize('update', $contract);

        $file = $request->file('file');
        $nextVersion = (int) ContractFile::query()->where('contract_id', $contract->id)->max('version') + 1;
        $path = $storage->storeContractFile($file, $contract->id, $nextVersion);

        ContractFile::query()->create([
            'contract_id' => $contract->id,
            'path' => $path,
            'disk' => $storage->disk(),
            'original_name' => $file->getClientOriginalName(),
            'version' => $nextVersion,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('contracts.show', $contract);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Website\StoreWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteRequest;
use App\Models\Resource;
use App\Models\Website;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WebsiteController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Website::class);

        $websites = Website::query()
            ->with('resource')
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('websites/pages/WebsiteIndex', [
            'websites' => $websites,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Website::class);

        return Inertia::render('websites/pages/WebsiteCreate', [
            'resourcesList' => Resource::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreWebsiteRequest $request): RedirectResponse
    {
        $this->authorize('create', Website::class);

        Website::query()->create($request->validated());

        return redirect()->route('websites.index');
    }

    public function show(Website $website): Response
    {
        $this->authorize('view', $website);

        $website->load(['resource', 'environments']);

        return Inertia::render('websites/pages/WebsiteShow', [
            'website' => $website,
        ]);
    }

    public function edit(Website $website): Response
    {
        $this->authorize('update', $website);

        return Inertia::render('websites/pages/WebsiteEdit', [
            'website' => $website,
            'resourcesList' => Resource::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateWebsiteRequest $request, Website $website): RedirectResponse
    {
        $this->authorize('update', $website);

        $website->update($request->validated());

        return redirect()->route('websites.show', $website);
    }

    public function destroy(Website $website): RedirectResponse
    {
        $this->authorize('delete', $website);

        $website->delete();

        return redirect()->route('websites.index');
    }
}

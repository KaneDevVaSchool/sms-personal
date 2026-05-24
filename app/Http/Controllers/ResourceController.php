<?php

namespace App\Http\Controllers;

use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Resource\UpdateResourceRequest;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Resource::class);

        $resources = Resource::query()
            ->with(['resourceType', 'ownerTeam'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')->toString()))
            ->when(
                $request->filled('resource_type_id'),
                fn ($q) => $q->where('resource_type_id', $request->integer('resource_type_id')),
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('resources/pages/ResourceIndex', [
            'resources' => $resources,
            'resourceTypes' => ResourceType::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'status' => $request->string('status')->toString(),
                'resource_type_id' => $request->input('resource_type_id'),
            ],
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Resource::class);

        return Inertia::render('resources/pages/ResourceCreate', [
            'resourceTypes' => ResourceType::query()->orderBy('name')->get(['id', 'name']),
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $this->authorize('create', Resource::class);

        Resource::query()->create($request->validated());

        return redirect()->route('resources.index');
    }

    public function show(Resource $resource): Response
    {
        $this->authorize('view', $resource);

        $resource->load(['resourceType', 'ownerTeam']);

        return Inertia::render('resources/pages/ResourceShow', [
            'resource' => $resource,
        ]);
    }

    public function edit(Resource $resource): Response
    {
        $this->authorize('update', $resource);

        return Inertia::render('resources/pages/ResourceEdit', [
            'resource' => $resource,
            'resourceTypes' => ResourceType::query()->orderBy('name')->get(['id', 'name']),
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateResourceRequest $request, Resource $resource): RedirectResponse
    {
        $this->authorize('update', $resource);

        $resource->update($request->validated());

        return redirect()->route('resources.show', $resource);
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $this->authorize('delete', $resource);

        $resource->delete();

        return redirect()->route('resources.index');
    }
}

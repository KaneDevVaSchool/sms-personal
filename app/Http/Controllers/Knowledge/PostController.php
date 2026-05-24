<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Http\Requests\Knowledge\StorePostRequest;
use App\Http\Requests\Knowledge\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::query()
            ->with(['category:id,name,slug', 'author:id,name'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $needle = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->string('q')->toString()).'%';
                $query->where(function ($q) use ($needle): void {
                    $q->where('title', 'like', $needle)->orWhere('body', 'like', $needle);
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('knowledge/pages/PostIndex', [
            'posts' => $posts,
            'filters' => [
                'q' => $request->string('q')->toString(),
            ],
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Post::class);

        return Inertia::render('knowledge/pages/PostCreate', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;

        Post::query()->create($data);

        return redirect()->route('knowledge.posts.index');
    }

    public function show(Post $post): Response
    {
        $this->authorize('view', $post);

        $post->load(['category', 'author:id,name', 'tags']);

        return Inertia::render('knowledge/pages/PostShow', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post): Response
    {
        $this->authorize('update', $post);

        return Inertia::render('knowledge/pages/PostEdit', [
            'post' => $post,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return redirect()->route('knowledge.posts.show', $post);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('knowledge.posts.index');
    }
}

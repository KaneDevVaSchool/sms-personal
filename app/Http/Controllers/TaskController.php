<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ReorderTasksRequest;
use App\Http\Requests\Project\StoreTaskRequest;
use App\Http\Requests\Project\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function kanban(Project $project): Response
    {
        $this->authorize('view', $project);

        $statusColumns = ['todo', 'in_progress', 'review', 'done'];

        $tasks = Task::query()
            ->where('project_id', $project->id)
            ->orderBy('status')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $grouped = $tasks->groupBy('status');

        $tasksByStatus = collect($statusColumns)
            ->mapWithKeys(fn (string $status) => [
                $status => $grouped->get($status, collect())->values()->all(),
            ])
            ->all();

        return Inertia::render('projects/pages/ProjectKanban', [
            'project' => $project,
            'tasksByStatus' => $tasksByStatus,
            'statusColumns' => $statusColumns,
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validated();

        Task::query()->create(array_merge($validated, [
            'project_id' => $project->id,
        ]));

        return redirect()->route('projects.tasks.index', $project);
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        abort_unless($task->project_id === $project->id, 404);

        $this->authorize('update', $project);

        $task->update($request->validated());

        return redirect()->route('projects.tasks.index', $project);
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        abort_unless($task->project_id === $project->id, 404);

        $this->authorize('update', $project);

        $task->delete();

        return redirect()->route('projects.tasks.index', $project);
    }

    public function reorder(ReorderTasksRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validated()['tasks'];

        DB::transaction(function () use ($project, $validated): void {
            foreach ($validated as $row) {
                $query = Task::query()
                    ->whereKey($row['id'])
                    ->where('project_id', $project->id);

                $updates = ['sort_order' => $row['sort_order']];
                if (array_key_exists('status', $row) && $row['status'] !== null) {
                    $updates['status'] = $row['status'];
                }

                $query->update($updates);
            }
        });

        return redirect()->route('projects.tasks.index', $project);
    }
}

<?php

use App\Http\Controllers\AiAccountController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Knowledge\CategoryController as KnowledgeCategoryController;
use App\Http\Controllers\Knowledge\PostController as KnowledgePostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Okr\KeyResultController as OkrKeyResultController;
use App\Http\Controllers\Okr\ObjectiveController as OkrObjectiveController;
use App\Http\Controllers\Okr\OkrCheckinController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Team\DepartmentController;
use App\Http\Controllers\Team\TeamUserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('notifications')->name('notifications.')->group(function (): void {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('{notification}/read', [NotificationController::class, 'markRead'])
        ->where('notification', '[^/]+')
        ->name('read');
});

Route::prefix('team')->name('team.')->group(function (): void {
    Route::get('users', [TeamUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [TeamUserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}', [TeamUserController::class, 'update'])->name('users.update');

    Route::resource('departments', DepartmentController::class)
        ->parameters(['departments' => 'department'])
        ->except(['show']);
});

Route::resource('resources', ResourceController::class);

Route::resource('websites', WebsiteController::class);

Route::resource('projects', ProjectController::class);

Route::get('projects/{project}/tasks', [TaskController::class, 'kanban'])->name('projects.tasks.index');
Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
Route::patch('projects/{project}/tasks/reorder', [TaskController::class, 'reorder'])->name('projects.tasks.reorder');
Route::patch('projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('projects.tasks.update');
Route::delete('projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('projects.tasks.destroy');

Route::prefix('knowledge')->name('knowledge.')->group(function (): void {
    Route::resource('posts', KnowledgePostController::class);
    Route::resource('categories', KnowledgeCategoryController::class)
        ->parameters(['categories' => 'category'])
        ->except(['show']);
});

Route::resource('contracts', ContractController::class);
Route::post('contracts/{contract}/files', [ContractController::class, 'storeFile'])->name('contracts.files.store');

Route::resource('ai-accounts', AiAccountController::class);

Route::resource('objectives', OkrObjectiveController::class);

Route::prefix('objectives/{objective}')->name('objectives.')->group(function (): void {
    Route::resource('key-results', OkrKeyResultController::class)
        ->parameters(['key-results' => 'key_result'])
        ->except(['show']);
});

Route::prefix('objectives/{objective}/key-results/{key_result}')
    ->name('objectives.key-results.checkins.')
    ->group(function (): void {
        Route::get('/', [OkrCheckinController::class, 'index'])->name('index');
        Route::get('/create', [OkrCheckinController::class, 'create'])->name('create');
        Route::post('/', [OkrCheckinController::class, 'store'])->name('store');
        Route::get('{checkin}/edit', [OkrCheckinController::class, 'edit'])->name('edit');
        Route::patch('{checkin}', [OkrCheckinController::class, 'update'])->name('update');
        Route::delete('{checkin}', [OkrCheckinController::class, 'destroy'])->name('destroy');
    });

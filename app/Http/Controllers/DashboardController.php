<?php

namespace App\Http\Controllers;

use App\Models\AiAccount;
use App\Models\Category;
use App\Models\Contract;
use App\Models\Department;
use App\Models\Objective;
use App\Models\Post;
use App\Models\Project;
use App\Models\Resource;
use App\Models\User;
use App\Models\Website;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'widgets' => [
                'team' => [
                    'users_count' => User::query()->count(),
                    'departments_count' => Department::query()->count(),
                ],
                'resources' => Resource::query()->count(),
                'websites' => Website::query()->count(),
                'projects' => Project::query()->count(),
                'knowledge' => [
                    'posts_count' => Post::query()->count(),
                    'categories_count' => Category::query()->count(),
                ],
                'contracts' => Contract::query()->count(),
                'ai_accounts' => AiAccount::query()->count(),
                'okrs' => Objective::query()->count(),
            ],
        ]);
    }
}

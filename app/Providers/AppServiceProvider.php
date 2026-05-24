<?php

namespace App\Providers;

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
use App\Policies\AiAccountPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ContractPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\ObjectivePolicy;
use App\Policies\PostPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\UserPolicy;
use App\Policies\WebsitePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(Resource::class, ResourcePolicy::class);
        Gate::policy(Website::class, WebsitePolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Contract::class, ContractPolicy::class);
        Gate::policy(AiAccount::class, AiAccountPolicy::class);
        Gate::policy(Objective::class, ObjectivePolicy::class);

        Vite::prefetch(concurrency: 3);

        if (! app()->bound('trace_id')) {
            app()->instance('trace_id', (string) Str::uuid());
        }
    }
}

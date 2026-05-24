<?php

namespace App\Services;

use App\Models\AiAccount;
use App\Models\Contract;
use App\Models\Resource;
use App\Models\Task;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Mail;

class AlertSchedulerService
{
    public function __construct(
        private readonly NotificationService $notifications,
    ) {}

    public function dispatchDailyAlerts(): void
    {
        $this->processResourceExpiries();
        $this->processSslExpiries();
        $this->processContractExpiries();
        $this->processAiQuotas();
        $this->processTaskDeadlines();
    }

    protected function processResourceExpiries(): void
    {
        foreach ([30, 7] as $days) {
            Resource::query()
                ->whereDate('expires_at', now()->addDays($days)->toDateString())
                ->each(function (Resource $resource) use ($days): void {
                    $this->notifyAdmins('resource.expiring', [
                        'resource_id' => $resource->id,
                        'name' => $resource->name,
                        'days' => $days,
                    ]);
                });
        }
    }

    protected function processSslExpiries(): void
    {
        foreach ([30, 7] as $days) {
            Website::query()
                ->whereDate('ssl_expires_at', now()->addDays($days)->toDateString())
                ->each(function (Website $website) use ($days): void {
                    $this->notifyAdmins('website.ssl_expiring', [
                        'website_id' => $website->id,
                        'name' => $website->name,
                        'days' => $days,
                    ]);
                });
        }
    }

    protected function processContractExpiries(): void
    {
        foreach ([60, 30] as $days) {
            Contract::query()
                ->whereDate('expires_at', now()->addDays($days)->toDateString())
                ->each(function (Contract $contract) use ($days): void {
                    $this->notifyAdmins('contract.expiring', [
                        'contract_id' => $contract->id,
                        'name' => $contract->name,
                        'days' => $days,
                    ]);
                });
        }
    }

    protected function processAiQuotas(): void
    {
        AiAccount::query()
            ->whereNotNull('quota_limit')
            ->where('quota_limit', '>', 0)
            ->get()
            ->each(function (AiAccount $account): void {
                $ratio = (float) $account->quota_used / (float) $account->quota_limit;
                if ($ratio >= 0.8) {
                    $this->notifyAdmins('ai.quota_warning', [
                        'ai_account_id' => $account->id,
                        'label' => $account->label,
                        'percent' => (int) round($ratio * 100),
                    ]);
                }
            });
    }

    protected function processTaskDeadlines(): void
    {
        Task::query()
            ->whereDate('due_date', now()->addDay()->toDateString())
            ->with('assignees')
            ->each(function (Task $task): void {
                foreach ($task->assignees as $user) {
                    $this->notifications->notifyInApp([$user], 'task.deadline', [
                        'task_id' => $task->id,
                        'title' => $task->title,
                    ]);
                }
            });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function notifyAdmins(string $type, array $data): void
    {
        $admins = User::role('admin')->get();
        if ($admins->isEmpty()) {
            return;
        }

        $this->notifications->notifyInApp($admins->all(), $type, $data);

        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::raw("OPS alert: {$type}", function ($message) use ($admin): void {
                    $message->to($admin->email)->subject('OPS Platform Alert');
                });
            }
        }
    }
}

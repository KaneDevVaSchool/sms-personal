<?php

namespace App\Jobs;

use App\Services\AlertSchedulerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunDailyOpsAlerts implements ShouldQueue
{
    use Queueable;

    public function handle(AlertSchedulerService $alerts): void
    {
        $alerts->dispatchDailyAlerts();
    }
}

<?php

use App\Jobs\RunDailyOpsAlerts;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new RunDailyOpsAlerts)->dailyAt('08:00');

Artisan::command('ops:alerts', function () {
    dispatch_sync(new RunDailyOpsAlerts);
    $this->info('OPS alerts processed.');
})->purpose('Run resource, SSL, contract, AI and task alert checks');

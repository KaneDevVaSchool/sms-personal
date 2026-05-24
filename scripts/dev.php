<?php

/**
 * Local dev stack: artisan serve, queue listener, and Vite.
 * Uses PHP + Symfony Process so `composer dev` works on Windows without npx/concurrently PATH issues.
 */

declare(ticks=1);

use Symfony\Component\Process\Process;

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
$php = PHP_BINARY;

/** @var list<Process> $processes */
$processes = [];

$start = function (string $label, Process $process) use (&$processes): void {
    $process->setTimeout(null);
    $process->start(function (string $type, string $buffer) use ($label): void {
        $lines = preg_split("/\r\n|\n|\r/", $buffer) ?: [];
        foreach ($lines as $line) {
            if ($line === '') {
                continue;
            }
            $stream = $type === Process::ERR ? STDERR : STDOUT;
            fwrite($stream, "[{$label}] {$line}\n");
        }
    });
    $processes[] = $process;
};

$start('server', new Process([$php, 'artisan', 'serve'], $root));
$start('queue', new Process([$php, 'artisan', 'queue:listen', '--tries=1', '--timeout=0'], $root));
$start('vite', Process::fromShellCommandline('npm run dev', $root));

$stopAll = function () use (&$processes): void {
    foreach ($processes as $process) {
        if ($process->isRunning()) {
            $process->stop(5);
        }
    }
};

if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGINT, function () use ($stopAll): void {
        $stopAll();
        exit(0);
    });
    pcntl_signal(SIGTERM, function () use ($stopAll): void {
        $stopAll();
        exit(0);
    });
}

register_shutdown_function($stopAll);

while (true) {
    if (function_exists('pcntl_signal_dispatch')) {
        pcntl_signal_dispatch();
    }

    foreach ($processes as $process) {
        if (! $process->isRunning()) {
            $code = $process->getExitCode();
            $stopAll();
            exit($code ?? 1);
        }
    }

    usleep(200_000);
}

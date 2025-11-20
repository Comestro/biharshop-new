<?php

namespace App\Console;

use App\Console\Commands\ReleaseDailyCommission;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ReleaseDailyCommission::class,
        \App\Console\Commands\RecomputeBinaryReferral::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('wallet:daily-commission')->dailyAt('00:10');
        $schedule->command('wallet:recompute-binary-referral')->dailyAt('00:20');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}

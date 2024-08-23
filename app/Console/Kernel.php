<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ImportFoodFacts::class,
    ];

  

protected function schedule(Schedule $schedule)
{
    $schedule->command('import:foodfacts')->daily();
}


protected function commands()
{
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
}

}
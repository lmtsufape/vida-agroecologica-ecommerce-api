<?php

namespace App\Console;

use App\Http\Controllers\Api\VendaController;
use App\Jobs\VerifyPedido;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Rodando o schedule');
        // $schedule->command('inspire')->hourly();
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->job(new VerifyPedido())->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

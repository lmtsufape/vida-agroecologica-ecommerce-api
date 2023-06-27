<?php

namespace App\Console;

use App\Http\Controllers\Api\VendaController;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        // $schedule->command('inspire')->hourly();
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->call(function () {
            $vendas = DB::table('vendas')->where('status','!=','Concluído');
            foreach ($vendas as $venda => $value) {
                $diff = Carbon::diffAsMinutes($venda->created_at);
                if($diff > 2) {
                    $ven = VendaController::cancelarCompra($venda->id);
                }

            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

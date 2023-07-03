<?php

namespace App\Jobs;

use App\Events\PedidoConfirmado;
use App\Http\Controllers\Api\VendaController;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public $tries = 2;
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vendas = DB::table('vendas')->where('status', '=', 'pagamento pendente')->get();
        foreach ($vendas as $venda) {
            $diff = Carbon::now()->diffInMinutes($venda->data_confirmacao);
            if ($diff >= 20) {
                VendaController::cancelarCompra($venda->id);
            }
        }
    }
}

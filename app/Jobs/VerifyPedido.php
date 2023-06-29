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
        $vendas = DB::table('vendas')->where('status','=','pedido realizado')->get();
        foreach ($vendas as $venda => $value) {
            $diff = Carbon::now()->diffInMinutes($value->created_at);
            if($diff >= 20) {
                $pedido = Venda::find($value->id);
                $pedido->cancel();
            }

        }
    }
}

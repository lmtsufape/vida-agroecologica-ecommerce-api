<?php

namespace App\Jobs;

use App\Http\Controllers\Api\VendaController;
use App\Models\Venda;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyPedidoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $venda;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Venda $venda)
    {
        $this->venda = $venda;
    }

    public $tries = 2;
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->venda->status == 'pagamento pendente') {
            app(VendaController::class)->cancelarCompra($this->venda->id);
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\PedidoConfirmado;
use App\Http\Controllers\Api\VendaController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CancelarPedido
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PedidoConfirmado  $event
     * @return void
     */
    public function handle(PedidoConfirmado $event, VendaController $vendaController)
    {
        if ($event->venda->status == 'pagamento pendente') {
            $vendaController->cancelarCompra('sistema', $event->venda->id);
        }
    }
}

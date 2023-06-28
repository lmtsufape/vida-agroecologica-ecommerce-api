<?php

namespace App\Listeners;

use App\Events\PedidoConfirmado;
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
    public function handle(PedidoConfirmado $event)
    {
        if ($event->venda->status == 'pagamento pendente') {
            $event->controller->cancelarCompra($event->venda->id, 'sistema');
        }
    }
}

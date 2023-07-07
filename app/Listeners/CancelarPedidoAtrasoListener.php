<?php

namespace App\Listeners;

use App\Events\PedidoConfirmadoEvent;
use App\Jobs\VerifyPedidoJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CancelarPedidoAtrasoListener
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
     * @param  \App\Events\PedidoConfirmadoEvent  $event
     * @return void
     */
    public function handle(PedidoConfirmadoEvent $event)
    {
        dispatch(new VerifyPedidoJob($event->venda))->delay(now()->addMinutes(20));
    }
}

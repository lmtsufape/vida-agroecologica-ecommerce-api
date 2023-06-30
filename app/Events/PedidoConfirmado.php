<?php

namespace App\Events;

use App\Http\Controllers\Api\VendaController;
use App\Models\Venda;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmado
{
    use Dispatchable, SerializesModels;

    public $venda, $controller;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Venda $venda, VendaController $controller)
    {
        $this->venda = $venda;
        $this->controller = $controller;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

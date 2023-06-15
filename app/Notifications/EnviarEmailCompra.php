<?php

namespace App\Notifications;

use App\Models\Venda;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnviarEmailCompra extends Notification implements ShouldQueue
{
    use Queueable;

    private $compra;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Venda $compra)
    {
        $this->compra = $compra;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = new MailMessage;
        $email->subject('Compra efetuada')
            ->greeting('Olá, ' . $this->compra->consumidor->user->name)
            ->line('Sua compra foi efetuada com sucesso!')
            ->line('Detalhes da compra:');

        foreach ($this->compra->itens as $item) {
            $email->line($item->produto->produtoTabelado->nome . ' | quantidade: ' . $item->quantidade . ' | preço unitário: R$ ' . $item->preco);
        }

        $email->line('subtotal: R$' . $this->compra->subtotal);
        $email->line('taxa de entrega: R$' . $this->compra->taxa_entrega);
        $email->line('total: R$' . $this->compra->total);
        return $email;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

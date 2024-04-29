<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self A_CONFIRMACAO()
 * @method static self A_RETIRADA()
 * @method static self A_ENVIO()
 * @method static self PA_PENDENTE()
 * @method static self PA_EXPIRADO()
 * @method static self PE_RECUSADO()
 * @method static self PE_CANCELADO()
 * @method static self PE_ENVIADO()
 * @method static self PE_ENTREGUE()
 * @method static self CO_RECUSADO()
 * @method static self CO_ANEXADO()
 */
class VendaStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'A_CONFIRMACAO' => 'aguardando confirmação',
            'A_RETIRADA' => 'aguardando retirada',
            'A_ENVIO' => 'aguardando envio',
            'PA_PENDENTE' => 'pagamento pendente',
            'PA_EXPIRADO' => 'pagamento expirado',
            'PE_RECUSADO' => 'pedido recusado',
            'PE_CANCELADO' => 'pedido cancelado',
            'PE_ENVIADO' => 'pedido enviado',
            'PE_ENTREGUE' => 'pedido entregue',
            'CO_RECUSADO' => 'comprovante recusado',
            'CO_ANEXADO' => 'comprovante anexado',
        ];
    }
}

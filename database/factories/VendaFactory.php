<?php

namespace Database\Factories;

use App\Models\ItemVenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => 'Concluído',
            'data_pedido' => fake()->dateTime('now'),
            'subtotal' => fake()->randomFloat(2, 0, 100),
            'taxa_entrega' => fake()->randomFloat(2, 0, 10),
            'total' => fake()->randomFloat(2, 0, 100),
            'comprovante_pagamento' => null,
            'forma_pagamento_id' => '1',
            'consumidor_id' => 1,
            'produtor_id' => 1,
        ];
    }

    public function withItens()
    {
        return $this->has(ItemVenda::factory()->count(3), 'itens');
    }
}

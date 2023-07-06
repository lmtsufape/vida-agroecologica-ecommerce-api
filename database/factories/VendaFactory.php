<?php

namespace Database\Factories;

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
            'forma_pagamento_id' => '1',
            'consumidor_id' => 1,
            'produtor_id' => 1,
        ];
    }
}

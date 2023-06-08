<?php

namespace Database\Factories;

use App\Models\ItemVenda;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemVenda>
 */
class ItemVendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tipo_unidade' => 'unidade',
            'quantidade' => fake()->numberBetween(1, 10),
            'preco' => fake()->randomFloat(4,1,20),
            'produto_id' => fake()->numberBetween(1, 10),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (ItemVenda $itemVenda) {
            $itemVenda->venda_id = $itemVenda->venda->id;
            $itemVenda->save();
        });
    }
}

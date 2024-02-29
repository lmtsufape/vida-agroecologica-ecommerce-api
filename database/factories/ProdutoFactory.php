<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'descricao' => fake()->realText(50),
            'titulo' => fake()->realText(50),
            'tipo_medida' => 'Unidade',
            'estoque' => fake()->numberBetween(1,20),
            'preco' => fake()->randomFloat(2,1,20),
            'custo' => fake()->randomFloat(2,1,20)
        ];
    }
}

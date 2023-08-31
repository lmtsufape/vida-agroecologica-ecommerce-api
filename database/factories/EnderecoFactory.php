<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'rua' => 'Rua das moreninhas',
            'numero' => 214,
            'cep' => '55290-00',
            'bairro_id' => 1,
            'addressable_type' => 'user',
            'addressable_id' => 5
        ];
    }
}

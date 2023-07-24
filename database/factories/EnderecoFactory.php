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
            'pais' => 'Brasil',
            'cidade' => 'Garanhuns',
            'estado' => 'Pernambuco',
            'rua' => 'Rua das moreninhas',
            'bairro' => 'Aluisio Pinto',
            'numero' => 214,
            'cep' => '55290-00',
            'bairro_id' => 1
        ];
    }
}

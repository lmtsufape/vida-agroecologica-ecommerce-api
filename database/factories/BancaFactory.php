<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banca>
 */
class BancaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "nome" => "Feira bicho",
            "descricao" => "Loja de frutas",
            "horarios_funcionamento" => [
                'domingo'        => ['08:00', '18:00'],
                'segunda-feira'  => ['08:00', '18:00'],
                'terca-feira'    => ['08:00', '18:00'],
                'quarta-feira'   => ['08:00', '18:00'],
                'quinta-feira'   => ['08:00', '18:00'],
                'sexta-feira'    => ['08:00', '18:00'],
                'sábado'         => ['08:00', '18:00'],
            ],
            "entrega" => true,
            "preco_minimo" => 1.00,
            "feira_id" => 1,
            "agricultor_id" => 4
        ];
    }
}

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
            "horario_abertura" => "00:00:00",
            "horario_fechamento" => "23:59:00",
            "funcionamento" => true,
            "preco_minimo" => 1.00,
            "produtor_id" => 3
        ];
    }
}

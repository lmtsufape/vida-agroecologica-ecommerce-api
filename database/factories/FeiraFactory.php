<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feira>
 */
class FeiraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => 'Feira de bonito',
            'horarios_funcionamento' => ['sexta', 'sabado', 'domingo'],
            'bairro_id' => 1,
            'associacao_id' => 1
        ];
    }
}

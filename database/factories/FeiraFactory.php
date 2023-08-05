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
            'funcionamento' => ['sexta', 'sabado', 'domingo'],
            'horario_abertura' => '10:00',
            'horario_fechamento' => '16:00',
            'bairro_id' => 1
        ];
    }
}

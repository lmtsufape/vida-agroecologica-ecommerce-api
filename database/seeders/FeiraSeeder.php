<?php

namespace Database\Seeders;

use App\Models\Feira;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feira::create([
            'funcionamento' => ['sexta', 'sabado', 'domingo'],
            'horario_abertura' => '20:10',
            'horario_fechamento' => '22:10',
            'bairro_id' => '1',
        ]);
    }
}

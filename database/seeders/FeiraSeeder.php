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
            'latitude' => -121.2322321,
            'longitude' => 23.3243,
            'funcionamento' => ['sexta', 'sabado', 'domingo'],
            'horario_abertura' => '2023-02-03',
            'horario_fechamento' => '2023-02-05',
        ]);
    }
}

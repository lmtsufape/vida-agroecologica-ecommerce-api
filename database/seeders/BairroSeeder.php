<?php

namespace Database\Seeders;

use App\Models\Bairro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bairro::create([
            'nome' => 'Feira da UFAPE',
            'taxa' => '0.1',
            'cidade_id' => '1',
        ]);
    }
}

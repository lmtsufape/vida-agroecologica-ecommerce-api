<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['nome' => 'Acre'],
            ['nome' => 'Alagoas'],
            ['nome' => 'Amapá'],
            ['nome' => 'Amazonas'],
            ['nome' => 'Bahia'],
            ['nome' => 'Ceará'],
            ['nome' => 'Distrito Federal'],
            ['nome' => 'Espírito Santo'],
            ['nome' => 'Goiás'],
            ['nome' => 'Maranhão'],
            ['nome' => 'Mato Grosso'],
            ['nome' => 'Mato Grosso do Sul'],
            ['nome' => 'Minas Gerais'],
            ['nome' => 'Pará'],
            ['nome' => 'Paraíba'],
            ['nome' => 'Paraná'],
            ['nome' => 'Pernambuco'],
            ['nome' => 'Piauí'],
            ['nome' => 'Rio de Janeiro'],
            ['nome' => 'Rio Grande do Norte'],
            ['nome' => 'Rio Grande do Sul'],
            ['nome' => 'Roraima'],
            ['nome' => 'Santa Catarina'],
            ['nome' => 'São Paulo'],
            ['nome' => 'Sergipe'],
            ['nome' => 'Tocantins']
        ];

        Estado::insert($estados);

    }

}

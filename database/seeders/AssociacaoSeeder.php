<?php

namespace Database\Seeders;

use App\Models\Associacao;
use App\Models\Contato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssociacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $associacao = Associacao::factory()->createOne();
        $contato = Contato::factory()->makeOne();
        $associacao->contato()->save($contato);
    }
}

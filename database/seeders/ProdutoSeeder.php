<?php

namespace Database\Seeders;

use App\Models\Banca;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $banca = Banca::find(1);
        for ($i=0; $i < 10; $i++) {
            $banca->produtos = Produto::factory()->create();
            $banca->produtos->categorias = Categoria::find(1);
        }
        //$banca->save();

    }
}

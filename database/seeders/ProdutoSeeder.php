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
        $produtos = ['Cenoura','Tomate','Ervilha','Espinafre'];
        $banca = Banca::find(1);
        for ($i=0; $i < sizeof($produtos); $i++) {
            $produto = Produto::factory()->create(['nome'=> $produtos[$i]]);
            $banca->produtos()->save($produto);
            $produto->categorias()->save( Categoria::find(1));
        }
        //$banca->save();

    }
}

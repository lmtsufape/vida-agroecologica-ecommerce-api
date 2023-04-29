<?php

namespace Database\Seeders;

use App\Models\Banca;
use App\Models\Produto;
use App\Models\ProdutoTabelado;
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
        for ($i = 0; $i < 10; $i++) {
            $produto = Produto::factory()->make(['banca_id' => $banca->id]);
            $produto->produtoTabelado()->associate(ProdutoTabelado::inRandomOrder()->first());
            $banca->produtos()->save($produto);
            $produto->save();
        }
        //$banca->save();

    }
}

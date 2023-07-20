<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        $this->call([
            CidadeSeeder::class,
            BairroSeeder::class,
            FeiraSeeder::class,
            ProdutorSeeder::class,
            ConsumidorSeeder::class,
            ProdutoTabeladoSeeder::class,
            ProdutoSeeder::class,
            FormaPagamentoSeeder::class,
            VendaSeeder::class,
        ]);
        Db::commit();
    }
}

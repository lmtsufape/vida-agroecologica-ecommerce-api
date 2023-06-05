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
            FeiraSeeder::class,
            ProdutorSeeder::class,
            ConsumidorSeeder::class,
            ProdutoTabeladoSeeder::class,
            ProdutoSeeder::class,
            BairroSeeder::class,
            FormaPagamentoSeeder::class
        ]);
        Db::commit();
    }
}

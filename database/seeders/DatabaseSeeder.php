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
            EstadoSeeder::class,
            CidadeSeeder::class,
            BairroSeeder::class,
            RoleSeeder::class,
            FormaPagamentoSeeder::class,
            AssociacaoSeeder::class,
            FeiraSeeder::class,
            UserSeeder::class,
            OrganizacaoControleSocialSeeder::class,
            PropriedadeSeeder::class,
            ProdutoTabeladoSeeder::class,
            ProdutoSeeder::class,
            VendaSeeder::class,
        ]);
        DB::commit();
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Endereco;
use App\Models\Produtor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $this->call([
            FeiraSeeder::class,
            ProdutorSeeder::class,
            ConsumidorSeeder::class,
            CategoriaSeeder::class,
            ProdutoSeeder::class,
            ProdutoTabeladoSeeder::class
        ]);
        Db::commit();
    }
}

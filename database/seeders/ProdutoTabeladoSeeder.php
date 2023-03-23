<?php

namespace Database\Seeders;

use App\Models\ProdutoTabelado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoTabeladoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // verifica se a tabela está vazia antes de preencher o banco
        if (DB::table('produtos_tabelados')->count() == 0) {
            $arquivo_csv = database_path('seeders/ProdutosTabelados.csv'); // caminho do arquivo CSV
            $dados_csv = array_map('str_getcsv', file($arquivo_csv)); // lê o arquivo CSV

            foreach ($dados_csv as $linha) {
                $produto = new ProdutoTabelado;
                $produto->nome = $linha[0];
                $produto->save();
            }
        }
    }
}

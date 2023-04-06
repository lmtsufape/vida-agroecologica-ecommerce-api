<?php

namespace Database\Seeders;

use App\Models\Bairro;
use App\Models\Categoria;
use App\Models\ProdutoTabelado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BairroSeeder extends Seeder
{
    public function run()
    {
        // verifica se a tabela está vazia antes de preencher o banco
        if (DB::table('bairros')->count() == 0) {
            $arquivo_csv = database_path('seeders/bairros.csv'); // caminho do arquivo CSV
            $dados_csv = array_map('str_getcsv', file($arquivo_csv)); // lê o arquivo CSV

            foreach ($dados_csv as $linha) {
                $bairro = new Bairro;
                $bairro->nome = $linha[0];
                $bairro->taxa = $linha[1];
                $bairro->save();
            }
        }
    }
}

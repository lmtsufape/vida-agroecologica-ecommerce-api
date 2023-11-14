<?php

namespace Database\Seeders;

use App\Models\ProdutoTabelado;
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
            $caminho_imagens = public_path('imagens/produtos/');
            
            foreach ($dados_csv as $linha) {
                $produto = new ProdutoTabelado;
                $produto->nome = $linha[0];
                if (array_key_exists(1, $linha)) {
                    $produto->categoria = $linha[1];
                }         
                $produto->save();
                $imagens = glob($caminho_imagens . $produto->nome . '.*'); // pega todos os arquivos que estão no $caminho_imagens e tem o nome $produto->nome, independente da extensão do arquivo.
                if (count($imagens) > 0) {
                    $produto->file()->create(['path' => str_replace(public_path(DIRECTORY_SEPARATOR), '', $imagens[0])]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\ProdutoTabelado;
use Directory;
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
            $caminho_imagens = join(DIRECTORY_SEPARATOR, ['storage', 'app', 'public', 'imagens', 'produtos']) . DIRECTORY_SEPARATOR; // O "DIRECTORY_SEPARATOR" é o separador de diretorio padrão, que pode variar de acordo com o sistema operacional, sendo este '/' ou '\'. o join() serve apara introduzir este separador entre cada elemento do array.
            
            foreach ($dados_csv as $linha) {
                $produto = new ProdutoTabelado;
                $produto->nome = $linha[0];
                if (array_key_exists(1, $linha)) {
                    $produto->categoria = $linha[1];
                }         
                $produto->save();
                $imagens = glob($caminho_imagens . $produto->nome . '.*'); // pega todos os arquivos que estão no $caminho_imagens e tem o nome $produto->nome, independente da extensão do arquivo.
                if (count($imagens) > 0) {
                    $produto->imagem()->create(['caminho' => $imagens[0]]);
                }
            }
        }
    }
}

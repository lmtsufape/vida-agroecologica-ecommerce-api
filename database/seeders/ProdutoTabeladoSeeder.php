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
        $arquivo_csv = database_path('seeders/ProdutosTabelados.csv');
        $dados_csv = array_map('str_getcsv', file($arquivo_csv));
        $caminho_imagens = public_path('imagens/produtos/');

        foreach ($dados_csv as $linha) {
            $produtoExistente = ProdutoTabelado::where('nome', $linha[0])->first();
            if (!$produtoExistente) {
                $produto = new ProdutoTabelado;
                $produto->nome = $linha[0];
                if (array_key_exists(1, $linha)) {
                    $produto->categoria = $linha[1];
                }
                $produto->save();

                $imagens = glob($caminho_imagens . $produto->nome . '.*');
                if (count($imagens) > 0) {
                    $produto->file()->create(['path' => str_replace(public_path(DIRECTORY_SEPARATOR), '', $imagens[0])]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use App\Models\Bairro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // verifica se a tabela está vazia antes de preencher o banco
        if (DB::table('bairros')->count() == 0) {
            $arquivo_csv = database_path('seeders/bairros.csv'); // caminho do arquivo CSV
            if (file_exists($arquivo_csv)) {
                $dados_csv = array_map('str_getcsv', file($arquivo_csv)); // lê o arquivo CSV

                foreach ($dados_csv as $linha) {
                    $bairro = new Bairro;
                    $bairro->nome = $linha[0];
                    $bairro->save();
                }
            } else {
                Bairro::create(['nome' => 'Teste', 'cidade_id' => '1']);
            }
        }
    }
}

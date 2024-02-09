<?php

namespace App\Console\Commands;

use App\Models\ProdutoTabelado;
use Illuminate\Console\Command;

class AtualizarImagensProduto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza todas as imagens dos produtos com base nas imagens armazenadas na pasta public/imagens/produtos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $produtos = ProdutoTabelado::doesntHave('file')->get();

        foreach ($produtos as $produto) {
            $imagem = glob(public_path('imagens/produtos/') . $produto->nome . '.*');

            if ($imagem) {
                $produto->file()->create(['path' => str_replace(public_path(DIRECTORY_SEPARATOR), '', $imagem[0])]);
            }
        }

        return Command::SUCCESS;
    }
}

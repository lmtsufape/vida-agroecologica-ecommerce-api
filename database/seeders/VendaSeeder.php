<?php

namespace Database\Seeders;

use App\Models\ItemVenda;
use App\Models\Venda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class VendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quantidadeItens = 3;
        Venda::factory()->count(5)
            ->has(
                ItemVenda::factory()
                    ->count($quantidadeItens)
                    ->sequence(fn (Sequence $sequence) => ['produto_id' => $sequence->index % $quantidadeItens + 1])
                    ->state(fn (array $attributes, Venda $venda) => ['venda_id' => $venda->id]),
                'itens'
            )->create();
    }
}

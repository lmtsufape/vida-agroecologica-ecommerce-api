<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formasPagamento = ['dinheiro', 'pix', 'cartão de crédito', 'cartão de débito', 'boleto bancário'];
        foreach($formasPagamento as $formaPagamento) {
            FormaPagamento::create(['tipo' => $formaPagamento]);
        }
    }
}

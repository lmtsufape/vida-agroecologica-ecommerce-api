<?php

namespace Database\Seeders;

use App\Models\ItemVenda;
use App\Models\Venda;
use Database\Factories\VendaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Venda::factory()->count(5)->hasItens(2)->create();
        
    }
}

<?php

namespace Database\Seeders;

use App\Models\Produtor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProdutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produtor = Produtor::factory()->create();
        $produtor->user()->update([
            'name' => 'Produtor',
            'email' => 'produtor@produtor.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'telefone' => fake()->numerify('##-#####-####')
        ]);
        $produtor->banca()->create([
            "nome" => "Feira bicho",
            "descricao" => "Loja de frutas",
            "horario_funcionamento" => "08:00:00",
            "horario_fechamento" => "18:00:00",
            "funcionamento" => true,
            "preco_minimo" => 1.00,
            "faz_entrega" => true,
            "feira_id" => "1"
        ]);
        $produtor->save();

        //Produtor::factory()->count(10)->create();
    }
}

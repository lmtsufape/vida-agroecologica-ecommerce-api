<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Endereco;
use App\Models\Produtor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $produtor = Produtor::factory()->create();
        $produtor->user()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'telefone'=> fake()->numerify('##-#####-####')
        ]);
       
        //$produtor->user()->endereco->create(Endereco::factory()->create());
        $produtor->save();
        $this->call([
            ProdutorSeeder::class,
            ConsumidorSeeder::class
        ]);
    }
}

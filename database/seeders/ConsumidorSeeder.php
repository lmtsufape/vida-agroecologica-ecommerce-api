<?php

namespace Database\Seeders;

use App\Models\Consumidor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConsumidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consumidor = Consumidor::factory()->create();
        $consumidor->user()->update([
            'name' => 'Consumidor',
            'email' => 'consumidor@consumidor.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'telefone' => fake()->numerify('##-#####-####')
        ]);
        $consumidor->save();
        //Consumidor::factory()->count(10)->create();
    }
}

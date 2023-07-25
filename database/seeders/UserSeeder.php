<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Contato::factory(4)->create();

        \App\Models\User::factory(1)->create([
            'email' => 'admin@admin.com',
            'cpf' => '999.999.999-99',
            'contato_id' => 1
        ]);

        \App\Models\User::factory(1)->create([
            'email' => 'presidente@presidente.com',
            'cpf' => '999.999.999-98',
            'contato_id' => 2
        ]);

        \App\Models\User::factory(1)->create([
            'email' => 'agricultor@agricultor.com',
            'cpf' => '999.999.999-97',
            'contato_id' => 3
        ]);

        \App\Models\User::factory(1)->create([
            'email' => 'consumidor@consumidor.com',
            'cpf' => '999.999.999-96',
            'contato_id' => 4
        ]);

        \App\Models\Banca::factory(1)->create();
    }
}

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

        $roles = \App\Models\Role::all();
        $emails = [
            'admin@admin.com',
            'presidente@presidente.com',
            'agricultor@agricultor.com',
            'consumidor@consumidor.com'
        ];

        foreach ($roles as $indice => $role) {
            $user = \App\Models\User::factory()->create([
                'email' => $emails[$indice],
                'cpf' => '999.999.999-9' . (9 - $indice),
                'contato_id' => $indice + 1
            ]);

            $user->roles()->attach($role);
        }

        \App\Models\Banca::factory()->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Banca;
use App\Models\Contato;
use App\Models\Role;
use App\Models\User;
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
        $contatos = Contato::factory(5)->make();
        $roles = Role::all();
        $emails = [
            'administrador@administrador.com',
            'presidente@presidente.com',
            'secretario@secretario.com',
            'agricultor@agricultor.com',
            'consumidor@consumidor.com'
        ];

        foreach ($roles as $indice => $role) {
            $user = User::factory()->create([
                'email' => $emails[$indice],
                'cpf' => '999.999.999-9' . (9 - $indice),
            ]);

            $user->roles()->attach($role);
            $user->contato()->save($contatos[$indice]);
        }

        $banca = Banca::factory()->create();
        $banca->formasPagamento()->attach(1);
    }
}

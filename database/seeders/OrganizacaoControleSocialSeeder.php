<?php

namespace Database\Seeders;

use App\Models\Contato;
use App\Models\OrganizacaoControleSocial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizacaoControleSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizacao = OrganizacaoControleSocial::factory()->createOne();
        $contato = Contato::factory()->makeOne();
        $organizacao->contato()->save($contato);
    }
}

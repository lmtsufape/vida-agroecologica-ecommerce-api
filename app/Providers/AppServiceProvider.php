<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\IOrganizacaoService;
use App\Services\OrganizacaoService;
use App\Models\OrganizacaoControleSocial;
use App\Interfaces\IAgricultorService;
use App\Services\AgricultorService;
use App\Interfaces\IAssociacaoService;
use App\Services\AssociacaoService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'produto_tabelado' => 'App\Models\ProdutoTabelado',
            'banca' => 'App\Models\Banca',
            'venda' => 'App\Models\Venda',
            'associacao' => 'App\Models\Associacao',
            'organizacao' => 'App\Models\OrganizacaoControleSocial',
            'propriedade' => 'App\Models\Propriedade',
            'feira' => 'App\Models\Feira'
        ]);
    }
}

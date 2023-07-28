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
        $this->app->bind(IOrganizacaoService::class, OrganizacaoService::class);
        $this->app->bind(IAgricultorService::class, AgricultorService::class);
        $this->app->bind(IAssociacaoService::class, AssociacaoService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'User' => 'App\Models\User',
            'Produtor' => 'App\Models\Produtor',
            'Consumidor' => 'App\Models\Consumidor',
            'Produto_tabelado' => 'App\Models\ProdutoTabelado',
            'Banca' => 'App\Models\Banca',
            'Venda' => 'App\Models\Venda',
            'OrganizacaoControleSocial' => 'App\Models\OrganizacaoControleSocial',
        ]);

    }
}

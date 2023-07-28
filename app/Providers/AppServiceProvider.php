<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
            'propriedade' => 'App\Models\Propriedade'
        ]);
    }
}

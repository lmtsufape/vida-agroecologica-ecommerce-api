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
            'User' => 'App\Models\User',
            'Produtor' => 'App\Models\Produtor',
            'Consumidor' => 'App\Models\Consumidor',
            'Produto_tabelado' => 'App\Models\ProdutoTabelado',
            'Banca' => 'App\Models\Banca',
            'Venda' => 'App\Models\Venda',
        ]);

    }
}

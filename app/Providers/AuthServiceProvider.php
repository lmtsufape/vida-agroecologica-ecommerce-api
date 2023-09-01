<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        App\Models\Banca::class => App\Policies\BancaPolicy::class,
        App\Models\User::class => App\Policies\UserPolicy::class,
        App\Models\Venda::class => App\Policies\VendaPolicy::class,
        App\models\Propriedade::class => App\Policies\PropriedadePolicy::class,
        App\models\Endereco::class => App\Policies\EnderecoPolicy::class,
        App\models\Produto::class => App\Policies\ProdutoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

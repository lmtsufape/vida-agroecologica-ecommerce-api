<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Venda;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Banca::class => BancaPolicy::class,
        Produtor::class => ProdutorPolicy::class,
        Consumidor::class => ConsumidorPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('anexar_comprovante', function (User $user, Venda $venda) {
            return $user->papel_type == 'Consumidor' && $user->papel->id === $venda->consumidor->id;
        });
        //
    }
}

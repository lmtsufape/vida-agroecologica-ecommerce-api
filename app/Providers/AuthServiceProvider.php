<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Consumidor;
use App\Policies\BancaPolicy;
use App\Policies\ProdutorPolicy;
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

        //
    }
}

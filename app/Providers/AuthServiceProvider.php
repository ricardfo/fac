<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Conveniado;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // admin 
        Gate::define('admin', function ($user) {

            $admins = explode(',', trim(config('fac.admins')));
            return ( in_array($user->codpes, $admins) and $user->codpes );

        });

        // conveniado
        Gate::define('conveniado', function ($user, $venda = null) {

            if(Gate::allows('admin')) return true;
            
            if($venda == null){
                if($user->conveniado()) return true;
            } else {
                $conveniado = $user->conveniado();
                if($conveniado) {
                    if($venda->conveniado_id == $conveniado->id) return true;
                }
            }
            return false;
        });

    }
}

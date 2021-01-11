<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\User' => 'App\Policies\AuthorizationPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(7));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        
        Passport::tokensCan([
            'adminUser' => 'Admin Section',
            'powerUser' => 'Power User Sections',
        ]);
        //

        Gate::resource('role', 'App\Policies\AuthorizationPolicy', [
            'sales' => 'sales',
            'admin' => 'admin',
            'superUser' => 'superUser',
            'operations' => 'operations',
        ]);
    }
}
